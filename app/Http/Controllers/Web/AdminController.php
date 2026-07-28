<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Endroit;
use App\Models\Objet;
use App\Models\QrCode;
use App\Models\Site;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function admin(): User
    {
        return User::findOrFail(session('admin_id'));
    }

    private function isLocalAdmin(): bool
    {
        return $this->admin()->role === 'local_admin';
    }

    private function allowedSiteIds(): array
    {
        if (!$this->isLocalAdmin()) return [];
        return $this->admin()->sites->pluck('id')->toArray();
    }

    private function guardSite($site): void
    {
        if ($this->isLocalAdmin() && !in_array($site->id, $this->allowedSiteIds())) {
            abort(403, 'Accès refusé à ce site.');
        }
    }

    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$recaptchaResponse) {
            return back()->withErrors(['email' => 'Please complete the CAPTCHA.'])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.'])->withInput($request->only('email'));
            }
            session(['admin_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('admin_id');
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function profile()
    {
        $user = $this->admin();
        return view('admin.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = $this->admin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => __('admin.profile_wrong_password')])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        view()->share('admin', $user);

        return back()->with('success', __('admin.profile_updated'));
    }

    // ── Visitors ──────────────────────────────────────

    public function visitors()
    {
        $visitors = \App\Models\Visitor::latest('last_seen_at')->paginate(15);
        return view('admin.visitors.index', compact('visitors'));
    }

    public function visitorShow(\App\Models\Visitor $visitor)
    {
        $scanLogs = $visitor->scanLogs()->with(['site', 'endroit'])->latest()->paginate(20);
        return view('admin.visitors.show', compact('visitor', 'scanLogs'));
    }

    public function visitorBlock(\App\Models\Visitor $visitor)
    {
        $visitor->update(['is_blocked' => !$visitor->is_blocked]);
        return back()->with('success', $visitor->is_blocked ? __('admin.visitor_blocked') : __('admin.visitor_unblocked'));
    }

    public function visitorDelete(\App\Models\Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('admin.visitors.index')->with('success', __('admin.visitor_deleted'));
    }

    public function dashboard()
    {
        $user = $this->admin();
        if ($this->isLocalAdmin()) {
            $siteIds = $this->allowedSiteIds();
            $sites = Site::whereIn('id', $siteIds)->count();
            $endroits = Endroit::whereIn('site_id', $siteIds)->count();
            $published = Endroit::whereIn('site_id', $siteIds)->where('is_published', true)->count();
        } else {
            $sites = Site::count();
            $endroits = Endroit::count();
            $published = Endroit::where('is_published', true)->count();
        }
        $users = $this->isLocalAdmin() ? null : User::count();

        $stats = [
            'visitors_count'       => \App\Models\Visitor::count(),
            'total_scans'          => \App\Models\Visitor::sum('total_scans'),
            'blocked_visitors'     => \App\Models\Visitor::where('is_blocked', true)->count(),
            'active_visitors_today'=> \App\Models\Visitor::where('last_seen_at', '>=', now()->startOfDay())->count(),
        ];

        return view('admin.dashboard', compact('sites', 'endroits', 'users', 'published', 'stats'));
    }

    public function wilayas()
    {
        $wilayas = Wilaya::withCount('sites')->latest()->paginate(15);
        return view('admin.wilayas.index', compact('wilayas'));
    }

    public function wilayaCreate()
    {
        return view('admin.wilayas.create');
    }

    public function wilayaStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.fr' => 'required|string',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'description' => 'nullable|array',
            'description.fr' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('wilayas', 'public');
        }

        Wilaya::create($validated);
        return redirect()->route('admin.wilayas.index')->with('success', 'Wilaya créée avec succès.');
    }

    public function wilayaShow(Wilaya $wilaya)
    {
        $wilaya->load('sites');
        return view('admin.wilayas.show', compact('wilaya'));
    }

    public function wilayaEdit(Wilaya $wilaya)
    {
        return view('admin.wilayas.edit', compact('wilaya'));
    }

    public function wilayaUpdate(Request $request, Wilaya $wilaya)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.fr' => 'required|string',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'description' => 'nullable|array',
            'description.fr' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('wilayas', 'public');
        }

        $wilaya->update($validated);
        return redirect()->route('admin.wilayas.index')->with('success', 'Wilaya mise à jour avec succès.');
    }

    public function wilayaDestroy(Wilaya $wilaya)
    {
        $wilaya->delete();
        return redirect()->route('admin.wilayas.index')->with('success', 'Wilaya supprimée avec succès.');
    }

    public function sites()
    {
        $query = Site::with('wilaya')->latest();
        if ($this->isLocalAdmin()) {
            $query->whereIn('id', $this->allowedSiteIds());
        }
        $sites = $query->paginate(15);

        return view('admin.sites.index', compact('sites'));
    }

    public function siteCreate()
    {
        $wilayas = Wilaya::all();

        return view('admin.sites.create', compact('wilayas'));
    }

    public function siteStore(Request $request)
    {
        $validated = $request->validate([
            'wilaya_id' => 'required|exists:wilayas,id',
            'name' => 'required|array',
            'name.fr' => 'required|string',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'description' => 'required|array',
            'description.fr' => 'required|string',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'cover_image' => 'nullable|image|max:51200',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'nullable|numeric',
            'is_published' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|max:51200',
            'audio_fr' => 'nullable|file|max:51200',
            'audio_ar' => 'nullable|file|max:51200',
            'audio_en' => 'nullable|file|max:51200',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('sites', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('sites', 'public');
            }
            $validated['images'] = $images;
        }

        $audioPaths = [];
        foreach (['fr', 'ar', 'en'] as $lang) {
            if ($request->hasFile("audio_{$lang}")) {
                $audioPaths[$lang] = $request->file("audio_{$lang}")->store('sites/audio', 'public');
            }
        }
        if ($audioPaths) $validated['audio_paths'] = $audioPaths;

        $validated['is_published'] = $request->boolean('is_published');

        Site::create($validated);

        return redirect()->route('admin.sites.index')->with('success', 'Site created successfully.');
    }

    public function siteShow(Site $site)
    {
        $this->guardSite($site);
        $site->load(['endroits', 'wilaya', 'qrCodes']);

        return view('admin.sites.show', compact('site'));
    }

    public function siteEdit(Site $site)
    {
        $this->guardSite($site);
        $wilayas = Wilaya::all();

        return view('admin.sites.edit', compact('site', 'wilayas'));
    }

    public function siteUpdate(Request $request, Site $site)
    {
        $this->guardSite($site);
        $validated = $request->validate([
            'wilaya_id' => 'required|exists:wilayas,id',
            'name' => 'required|array',
            'name.fr' => 'required|string',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'description' => 'nullable|array',
            'cover_image' => 'nullable|image|max:51200',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'nullable|numeric',
            'is_published' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|max:51200',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
            'audio_fr' => 'nullable|file|max:51200',
            'audio_ar' => 'nullable|file|max:51200',
            'audio_en' => 'nullable|file|max:51200',
            'existing_audio_fr' => 'nullable|string',
            'existing_audio_ar' => 'nullable|string',
            'existing_audio_en' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('sites', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published');

        // Handle images
        $existingImages = $request->input('existing_images', []);
        $removeImages = $request->input('remove_images', []);
        $existingImages = array_diff($existingImages, $removeImages);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $existingImages[] = $image->store('sites', 'public');
            }
        }
        $validated['images'] = array_values($existingImages);

        // Handle audio per language
        $audioPaths = is_array($site->audio_paths) ? $site->audio_paths : [];
        foreach (['fr', 'ar', 'en'] as $lang) {
            $existingKey = "existing_audio_{$lang}";
            if ($request->input($existingKey)) {
                $audioPaths[$lang] = $request->input($existingKey);
            }
            if ($request->hasFile("audio_{$lang}")) {
                $audioPaths[$lang] = $request->file("audio_{$lang}")->store('sites/audio', 'public');
            }
        }
        $validated['audio_paths'] = $audioPaths;

        $site->update($validated);

        return redirect()->route('admin.sites.index')->with('success', 'Site updated successfully.');
    }

    public function siteDestroy(Site $site)
    {
        $this->guardSite($site);
        $site->delete();

        return redirect()->route('admin.sites.index')->with('success', 'Site deleted successfully.');
    }

    public function endroits()
    {
        $query = Endroit::with('site')->latest();
        if ($this->isLocalAdmin()) {
            $query->whereIn('site_id', $this->allowedSiteIds());
        }
        $endroits = $query->paginate(15);

        return view('admin.endroits.index', compact('endroits'));
    }

    public function endroitCreate()
    {
        $sites = Site::all();
        if ($this->isLocalAdmin()) {
            $sites = Site::whereIn('id', $this->allowedSiteIds())->get();
        }

        return view('admin.endroits.create', compact('sites'));
    }

    public function endroitStore(Request $request)
    {
        if ($this->isLocalAdmin() && !in_array($request->input('site_id'), $this->allowedSiteIds())) {
            abort(403, 'Vous ne pouvez pas créer d\'endroit sur ce site.');
        }
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.ar' => 'required|string',
            'title.en' => 'required|string',
            'description' => 'required|array',
            'description.fr' => 'required|string',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'nullable|numeric',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'audio_fr' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_ar' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_en' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('endroits/images', 'public');
            }
        }
        $validated['images'] = $images;

        $audios = [];
        foreach (['fr', 'ar', 'en'] as $lang) {
            if ($request->hasFile("audio_{$lang}")) {
                $audios[$lang] = $request->file("audio_{$lang}")->store('endroits/audio', 'public');
            }
        }
        $validated['audio_paths'] = $audios;

        unset($validated['audio_fr'], $validated['audio_ar'], $validated['audio_en']);

        Endroit::create($validated);

        return redirect()->route('admin.endroits.index')->with('success', 'Endroit created successfully.');
    }

    public function endroitShow(Endroit $endroit)
    {
        $endroit->load('site');
        $this->guardSite($endroit->site);

        return view('admin.endroits.show', compact('endroit'));
    }

    public function endroitEdit(Endroit $endroit)
    {
        $this->guardSite($endroit->site);
        $sites = Site::all();
        if ($this->isLocalAdmin()) {
            $sites = Site::whereIn('id', $this->allowedSiteIds())->get();
        }

        return view('admin.endroits.edit', compact('endroit', 'sites'));
    }

    public function endroitUpdate(Request $request, Endroit $endroit)
    {
        $this->guardSite($endroit->site);
        if ($this->isLocalAdmin() && !in_array($request->input('site_id'), $this->allowedSiteIds())) {
            abort(403, 'Vous ne pouvez pas déplacer un endroit vers un autre site.');
        }
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.ar' => 'required|string',
            'title.en' => 'required|string',
            'description' => 'required|array',
            'description.fr' => 'required|string',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'nullable|numeric',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'audio_fr' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_ar' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_en' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $existingImages = $request->input('existing_images', []);
        $removeImages = $request->input('remove_images', []);
        $existingImages = array_values(array_diff($existingImages, $removeImages));
        $newImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $newImages[] = $file->store('endroits/images', 'public');
            }
        }
        $validated['images'] = array_merge($existingImages, $newImages);

        $previousAudios = is_array($endroit->audio_paths) ? $endroit->audio_paths : [];
        $audios = [];
        foreach (['fr', 'ar', 'en'] as $lang) {
            if ($request->hasFile("audio_{$lang}")) {
                $audios[$lang] = $request->file("audio_{$lang}")->store('endroits/audio', 'public');
            } elseif ($request->input("existing_audio_{$lang}")) {
                $audios[$lang] = $request->input("existing_audio_{$lang}");
            } elseif (isset($previousAudios[$lang])) {
                $audios[$lang] = $previousAudios[$lang];
            }
        }
        $validated['audio_paths'] = $audios;

        unset($validated['audio_fr'], $validated['audio_ar'], $validated['audio_en']);

        $endroit->update($validated);

        return redirect()->route('admin.endroits.show', $endroit)->with('success', 'Endroit updated successfully.');
    }

    public function endroitDestroy(Endroit $endroit)
    {
        $this->guardSite($endroit->site);

        if ($endroit->objets()->count() > 0) {
            return redirect()->route('admin.endroits.show', $endroit)
                ->with('error', 'Cet endroit contient des objets. Supprimez-les d\'abord.');
        }

        $endroit->delete();

        return redirect()->route('admin.endroits.index')->with('success', 'Endroit deleted successfully.');
    }

    public function objets()
    {
        $query = Objet::with('endroit.site')->latest();
        if ($this->isLocalAdmin()) {
            $query->whereHas('endroit', function ($q) {
                $q->whereIn('site_id', $this->allowedSiteIds());
            });
        }
        $objets = $query->paginate(15);

        return view('admin.objets.index', compact('objets'));
    }

    public function objetCreate()
    {
        $endroits = Endroit::with('site')->get();
        if ($this->isLocalAdmin()) {
            $endroits = $endroits->filter(fn ($e) => in_array($e->site_id, $this->allowedSiteIds()));
        }

        return view('admin.objets.create', compact('endroits'));
    }

    public function objetStore(Request $request)
    {
        $endroit = Endroit::findOrFail($request->input('endroit_id'));
        $this->guardSite($endroit->site);

        $validated = $request->validate([
            'endroit_id' => 'required|exists:endroits,id',
            'title' => 'required|array',
            'title.fr' => 'required|string',
            'title.ar' => 'required|string',
            'title.en' => 'required|string',
            'description' => 'required|array',
            'description.fr' => 'required|string',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'materiau' => 'nullable|string',
            'periode' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'audio_fr' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_ar' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_en' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('objets/images', 'public');
            }
        }
        $validated['images'] = $images;

        $audios = [];
        foreach (['fr', 'ar', 'en'] as $lang) {
            if ($request->hasFile("audio_{$lang}")) {
                $audios[$lang] = $request->file("audio_{$lang}")->store('objets/audio', 'public');
            }
        }
        $validated['audio_paths'] = $audios;

        unset($validated['audio_fr'], $validated['audio_ar'], $validated['audio_en']);

        $objet = Objet::create($validated);

        $objet->qr_code_id = 'OBJ-' . str_pad($objet->id, 4, '0', STR_PAD_LEFT);
        $objet->save();

        $endroit->site->qrCodes()->create([
            'qr_code_id' => $objet->qr_code_id,
            'type' => 'objet',
            'objet_id' => $objet->id,
            'site_id' => $endroit->site_id,
            'endroit_id' => $endroit->id,
        ]);

        return redirect()->route('admin.endroits.show', $endroit)->with('success', 'Objet créé avec succès.');
    }

    public function objetShow(Objet $objet)
    {
        $objet->load('endroit.site');
        $this->guardSite($objet->endroit->site);

        return view('admin.objets.show', compact('objet'));
    }

    public function objetEdit(Objet $objet)
    {
        $objet->load('endroit.site');
        $this->guardSite($objet->endroit->site);

        $endroits = Endroit::with('site')->get();
        if ($this->isLocalAdmin()) {
            $endroits = $endroits->filter(fn ($e) => in_array($e->site_id, $this->allowedSiteIds()));
        }

        return view('admin.objets.edit', compact('objet', 'endroits'));
    }

    public function objetUpdate(Request $request, Objet $objet)
    {
        $objet->load('endroit.site');
        $this->guardSite($objet->endroit->site);

        $validated = $request->validate([
            'title' => 'sometimes|array',
            'title.fr' => 'required_with:title|string',
            'title.ar' => 'required_with:title|string',
            'title.en' => 'required_with:title|string',
            'description' => 'sometimes|array',
            'description.fr' => 'required_with:description|string',
            'description.ar' => 'required_with:description|string',
            'description.en' => 'required_with:description|string',
            'materiau' => 'nullable|string',
            'periode' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'audio_fr' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_ar' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'audio_en' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('images')) {
            $this->deleteObjetPhotos($objet);
            $images = [];
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('objets/' . $objet->id, 'public');
            }
            $validated['images'] = $images;
        } else {
            unset($validated['images']);
        }

        $audioPaths = $objet->audio_paths ?? [];
        foreach (['fr', 'ar', 'en'] as $lang) {
            if ($request->hasFile("audio_{$lang}")) {
                if (isset($audioPaths[$lang])) {
                    Storage::disk('public')->delete($audioPaths[$lang]);
                }
                $audioPaths[$lang] = $request->file("audio_{$lang}")->store('objets/' . $objet->id . '/audio', 'public');
            }
        }
        $validated['audio_paths'] = $audioPaths;

        unset($validated['audio_fr'], $validated['audio_ar'], $validated['audio_en']);

        $objet->update($validated);

        return redirect()->route('admin.objets.show', $objet)->with('success', 'Objet mis à jour.');
    }

    public function objetDestroy(Objet $objet)
    {
        $objet->load('endroit.site');
        $this->guardSite($objet->endroit->site);

        $this->deleteObjetPhotos($objet);
        $audioPaths = $objet->audio_paths ?? [];
        foreach ($audioPaths as $path) {
            Storage::disk('public')->delete($path);
        }

        $objet->delete();

        return redirect()->route('admin.endroits.show', $objet->endroit_id)->with('success', 'Objet supprimé.');
    }

    private function deleteObjetPhotos(Objet $objet): void
    {
        $photos = $objet->images ?? [];
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo);
        }
    }

    public function qrCodes()
    {
        $query = QrCode::with(['site', 'endroit', 'objet.endroit.site'])
            ->whereHas('site', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->latest();
        if ($this->isLocalAdmin()) {
            $query->whereIn('site_id', $this->allowedSiteIds());
        }
        $qrCodes = $query->paginate(15);
        $sites = Site::whereNull('deleted_at')->get();
        if ($this->isLocalAdmin()) {
            $sites = Site::whereNull('deleted_at')->whereIn('id', $this->allowedSiteIds())->get();
        }

        return view('admin.qr', compact('qrCodes', 'sites'));
    }

    public function qrGenerate(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
        ]);

        if ($this->isLocalAdmin() && !in_array($validated['site_id'], $this->allowedSiteIds())) {
            abort(403);
        }

        $siteId = $validated['site_id'];
        $created = 0;

        $existingSiteQr = QrCode::where('site_id', $siteId)->where('type', 'site')->exists();
        if (!$existingSiteQr) {
            QrCode::create([
                'qr_code_id' => 'SITE-' . str_pad($siteId, 4, '0', STR_PAD_LEFT),
                'type' => 'site',
                'site_id' => $siteId,
                'endroit_id' => null,
            ]);
            $created++;
        }

        $endroits = Endroit::where('site_id', $siteId)->get();
        foreach ($endroits as $endroit) {
            $exists = QrCode::where('site_id', $siteId)->where('endroit_id', $endroit->id)->exists();
            if (!$exists) {
                QrCode::create([
                    'qr_code_id' => 'END-' . str_pad($endroit->id, 4, '0', STR_PAD_LEFT),
                    'type' => 'endroit',
                    'site_id' => $siteId,
                    'endroit_id' => $endroit->id,
                ]);
                $created++;
            }
        }

        $msg = $created > 0
            ? __('admin.qr_generated_count', ['count' => $created])
            : __('admin.qr_already_exists');

        return redirect()->route('admin.qr.index')->with('success', $msg);
    }

    public function qrExport(Site $site)
    {
        $this->guardSite($site);
        $qrCodes = QrCode::with(['site', 'endroit'])->where('site_id', $site->id)->get();

        return view('admin.qr-export', compact('site', 'qrCodes'));
    }

    public function users()
    {
        $users = User::with('sites')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        $sites = Site::with('wilaya')->get();

        return view('admin.users.create', compact('sites'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,local_admin',
            'site_id' => 'required_if:role,local_admin|nullable|exists:sites,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $siteId = $validated['site_id'] ?? null;
        unset($validated['site_id']);

        $user = User::create($validated);

        if ($validated['role'] === 'local_admin' && $siteId) {
            $user->sites()->attach($siteId);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function userEdit(User $user)
    {
        $sites = Site::with('wilaya')->get();
        $user->load('sites');

        return view('admin.users.edit', compact('user', 'sites'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,local_admin',
            'site_id' => 'required_if:role,local_admin|nullable|exists:sites,id',
        ];

        if (!empty($request->input('password'))) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $siteId = $validated['site_id'] ?? null;
        unset($validated['site_id']);

        $user->update($validated);

        if ($validated['role'] === 'local_admin' && $siteId) {
            $user->sites()->sync([$siteId]);
        } else {
            $user->sites()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function userDestroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function userToggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'User status toggled successfully.');
    }
}

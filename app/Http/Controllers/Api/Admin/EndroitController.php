<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Traits\AuthorizesSite;
use App\Http\Controllers\Api\Controller;
use App\Models\Endroit;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EndroitController extends Controller
{
    use AuthorizesSite;

    public function index(Request $request, int $siteId): JsonResponse
    {
        $site = Site::findOrFail($siteId);
        $this->authorizeSite($request, $site);

        $endroits = Endroit::where('site_id', $siteId)->paginate(25);

        return response()->json($endroits);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|array',
            'title.ar' => 'required|string',
            'title.fr' => 'required|string',
            'title.en' => 'required|string',
            'description' => 'nullable|array',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
        ]);

        $site = Site::findOrFail($request->site_id);
        $this->authorizeSite($request, $site);

        $endroit = Endroit::create($request->only([
            'site_id', 'title', 'description', 'latitude', 'longitude', 'altitude',
        ]));

        $endroit->qr_code_id = 'END-' . str_pad($endroit->id, 4, '0', STR_PAD_LEFT);
        $endroit->save();

        $endroit->site()->first()->qrCodes()->create([
            'qr_code_id' => $endroit->qr_code_id,
            'type' => 'endroit',
            'endroit_id' => $endroit->id,
            'site_id' => $endroit->site_id,
        ]);

        return response()->json($endroit, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $endroit = Endroit::with('site')->findOrFail($id);
        $this->authorizeSite($request, $endroit->site);

        return response()->json($endroit);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $endroit = Endroit::with('site')->findOrFail($id);
        $this->authorizeSite($request, $endroit->site);

        $request->validate([
            'title' => 'sometimes|array',
            'description' => 'nullable|array',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'images' => 'nullable|array',
            'audio_paths' => 'nullable|array',
            'is_published' => 'sometimes|boolean',
        ]);

        $endroit->update($request->only([
            'title', 'description', 'latitude', 'longitude', 'altitude',
            'images', 'audio_paths', 'is_published',
        ]));

        return response()->json($endroit->fresh());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $endroit = Endroit::with('site')->findOrFail($id);
        $this->authorizeSite($request, $endroit->site);

        if ($endroit->objets()->count() > 0) {
            return response()->json([
                'message' => 'Cet endroit contient des objets. Supprimez-les d\'abord.',
            ], 409);
        }

        $this->deleteMediaFiles($endroit);
        $endroit->delete();

        return response()->json(['message' => 'Endroit supprimé.']);
    }

    public function uploadMedia(Request $request, int $id): JsonResponse
    {
        $endroit = Endroit::with('site')->findOrFail($id);
        $this->authorizeSite($request, $endroit->site);

        $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'file|max:10240|mimes:jpg,jpeg,png,webp,gif',
            'audio_ar' => 'nullable|file|max:20480|mimes:mp3,wav,ogg,m4a',
            'audio_fr' => 'nullable|file|max:20480|mimes:mp3,wav,ogg,m4a',
            'audio_en' => 'nullable|file|max:20480|mimes:mp3,wav,ogg,m4a',
        ]);

        $images = $endroit->images ?? [];

        if ($request->hasFile('photos')) {
            $this->deletePhotos($endroit);
            $images = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('endroits/' . $endroit->id, 'public');
                $images[] = $path;
            }
            $endroit->images = $images;
        }

        $audioPaths = $endroit->audio_paths ?? [];

        foreach (['audio_ar', 'audio_fr', 'audio_en'] as $lang) {
            if ($request->hasFile($lang)) {
                $langCode = explode('_', $lang)[1];
                if (isset($audioPaths[$langCode])) {
                    Storage::disk('public')->delete($audioPaths[$langCode]);
                }
                $audioPaths[$langCode] = $request->file($lang)->store('endroits/' . $endroit->id . '/audio', 'public');
            }
        }

        $endroit->audio_paths = $audioPaths;
        $endroit->save();

        return response()->json($endroit->fresh());
    }

    private function deletePhotos(Endroit $endroit): void
    {
        $photos = $endroit->images ?? [];
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo);
        }
    }

    private function deleteMediaFiles(Endroit $endroit): void
    {
        $this->deletePhotos($endroit);
        $audioPaths = $endroit->audio_paths ?? [];
        foreach ($audioPaths as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}

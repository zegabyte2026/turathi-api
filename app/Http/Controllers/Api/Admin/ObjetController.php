<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Traits\AuthorizesSite;
use App\Http\Controllers\Api\Controller;
use App\Models\Endroit;
use App\Models\Objet;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObjetController extends Controller
{
    use AuthorizesSite;

    public function index(Request $request, int $endroitId): JsonResponse
    {
        $endroit = Endroit::with('site')->findOrFail($endroitId);
        $this->authorizeSite($request, $endroit->site);

        $objets = Objet::where('endroit_id', $endroitId)->paginate(25);

        return response()->json($objets);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'endroit_id' => 'required|exists:endroits,id',
            'title' => 'required|array',
            'title.ar' => 'required|string',
            'title.fr' => 'required|string',
            'title.en' => 'required|string',
            'description' => 'nullable|array',
            'materiau' => 'nullable|string',
            'periode' => 'nullable|string',
            'dimensions' => 'nullable|string',
        ]);

        $endroit = Endroit::with('site')->findOrFail($request->endroit_id);
        $this->authorizeSite($request, $endroit->site);

        $objet = Objet::create($request->only([
            'endroit_id', 'title', 'description', 'materiau', 'periode', 'dimensions',
        ]));

        $objet->qr_code_id = 'OBJ-' . str_pad($objet->id, 4, '0', STR_PAD_LEFT);
        $objet->save();

        $endroit->site->qrCodes()->create([
            'qr_code_id' => $objet->qr_code_id,
            'type' => 'objet',
            'objet_id' => $objet->id,
            'site_id' => $endroit->site_id,
            'endroit_id' => $endroit->id,
        ]);

        return response()->json($objet, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $objet = Objet::with(['endroit.site'])->findOrFail($id);
        $this->authorizeSite($request, $objet->endroit->site);

        return response()->json($objet);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $objet = Objet::with('endroit.site')->findOrFail($id);
        $this->authorizeSite($request, $objet->endroit->site);

        $request->validate([
            'title' => 'sometimes|array',
            'description' => 'nullable|array',
            'materiau' => 'nullable|string',
            'periode' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'images' => 'nullable|array',
            'audio_paths' => 'nullable|array',
            'is_published' => 'sometimes|boolean',
        ]);

        $objet->update($request->only([
            'title', 'description', 'materiau', 'periode', 'dimensions',
            'images', 'audio_paths', 'is_published',
        ]));

        return response()->json($objet->fresh());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $objet = Objet::with('endroit.site')->findOrFail($id);
        $this->authorizeSite($request, $objet->endroit->site);

        $this->deleteMediaFiles($objet);
        $objet->delete();

        return response()->json(['message' => 'Objet supprimé.']);
    }

    public function uploadMedia(Request $request, int $id): JsonResponse
    {
        $objet = Objet::with('endroit.site')->findOrFail($id);
        $this->authorizeSite($request, $objet->endroit->site);

        $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'file|max:10240|mimes:jpg,jpeg,png,webp,gif',
            'audio_ar' => 'nullable|file|max:20480|mimes:mp3,wav,ogg,m4a',
            'audio_fr' => 'nullable|file|max:20480|mimes:mp3,wav,ogg,m4a',
            'audio_en' => 'nullable|file|max:20480|mimes:mp3,wav,ogg,m4a',
        ]);

        $images = $objet->images ?? [];

        if ($request->hasFile('photos')) {
            $this->deletePhotos($objet);
            $images = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('objets/' . $objet->id, 'public');
                $images[] = $path;
            }
            $objet->images = $images;
        }

        $audioPaths = $objet->audio_paths ?? [];

        foreach (['audio_ar', 'audio_fr', 'audio_en'] as $lang) {
            if ($request->hasFile($lang)) {
                $langCode = explode('_', $lang)[1];
                if (isset($audioPaths[$langCode])) {
                    Storage::disk('public')->delete($audioPaths[$langCode]);
                }
                $audioPaths[$langCode] = $request->file($lang)->store('objets/' . $objet->id . '/audio', 'public');
            }
        }

        $objet->audio_paths = $audioPaths;
        $objet->save();

        return response()->json($objet->fresh());
    }

    private function deletePhotos(Objet $objet): void
    {
        $photos = $objet->images ?? [];
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo);
        }
    }

    private function deleteMediaFiles(Objet $objet): void
    {
        $this->deletePhotos($objet);
        $audioPaths = $objet->audio_paths ?? [];
        foreach ($audioPaths as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}

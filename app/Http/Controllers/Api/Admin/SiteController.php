<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Traits\AuthorizesSite;
use App\Http\Controllers\Api\Controller;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    use AuthorizesSite;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Site::with(['wilaya', 'packVersions' => fn($q) => $q->latest()->limit(1)]);

        if (!$user->isSuperAdmin()) {
            $query->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        }

        $sites = $query->paginate(25);

        return response()->json($sites);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'wilaya_id' => 'required|exists:wilayas,id',
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.fr' => 'required|string',
            'name.en' => 'required|string',
            'description' => 'nullable|array',
            'cover_image' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
        ]);

        $site = Site::create($request->only([
            'wilaya_id', 'name', 'description', 'cover_image',
            'latitude', 'longitude', 'altitude',
        ]));

        $site->qr_code_id = 'SITE-' . str_pad($site->id, 4, '0', STR_PAD_LEFT);
        $site->save();

        $site->qrCodes()->create([
            'qr_code_id' => $site->qr_code_id,
            'type' => 'site',
            'site_id' => $site->id,
        ]);

        return response()->json($site->load('wilaya'), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $site = Site::with(['wilaya', 'qrCodes'])->findOrFail($id);
        $this->authorizeSite($request, $site);

        return response()->json($site);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $site = Site::findOrFail($id);
        $this->authorizeSite($request, $site);

        $request->validate([
            'name' => 'sometimes|array',
            'name.ar' => 'sometimes|string',
            'name.fr' => 'sometimes|string',
            'name.en' => 'sometimes|string',
            'description' => 'nullable|array',
            'cover_image' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
        ]);

        $site->update($request->only([
            'name', 'description', 'cover_image',
            'latitude', 'longitude', 'altitude',
        ]));

        return response()->json($site->fresh(['wilaya']));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $site = Site::findOrFail($id);

        if ($site->endroits()->count() > 0) {
            return response()->json(['message' => 'Impossible de supprimer un site contenant des endroits.'], 409);
        }

        $site->delete();

        return response()->json(['message' => 'Site supprimé.']);
    }
}

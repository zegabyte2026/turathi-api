<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Models\PackVersion;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackController extends Controller
{
    public function compile(Request $request, int $siteId): JsonResponse
    {
        $site = Site::with('endroits')->findOrFail($siteId);

        $pack = PackVersion::create([
            'site_id' => $site->id,
            'version' => $this->nextVersion($site->version_pack),
            'hash' => Str::random(32),
            'status' => 'compiling',
            'endroits_count' => $site->endroits()->count(),
        ]);

        // TODO: dispatch CompilePackJob::class via Queue
        // dispatch(new CompilePackJob($site, $pack));

        return response()->json([
            'pack' => $pack,
            'message' => 'Compilation lancée en arrière-plan.',
        ]);
    }

    public function status(Request $request, int $siteId): JsonResponse
    {
        $pack = PackVersion::where('site_id', $siteId)
            ->latest()
            ->first();

        if (!$pack) {
            return response()->json(['status' => 'none', 'message' => 'Aucune compilation.']);
        }

        return response()->json($pack);
    }

    private function nextVersion(string $current): string
    {
        $parts = explode('.', $current);
        $parts[2] = ($parts[2] ?? 0) + 1;
        return implode('.', $parts);
    }
}

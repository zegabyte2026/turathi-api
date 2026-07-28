<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Controller;
use App\Models\Endroit;
use App\Models\Objet;
use App\Models\QrCode;
use App\Models\Site;
use App\Models\Wilaya;
use App\Services\GeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function wilayas(): JsonResponse
    {
        $wilayas = Wilaya::withCount('sites')->get();

        return response()->json($wilayas);
    }

    public function sites(): JsonResponse
    {
        $sites = Site::withCount('endroits')
            ->where('is_published', true)
            ->get();

        return response()->json($sites);
    }

    public function wilayaSites(int $wilayaId): JsonResponse
    {
        $wilaya = Wilaya::findOrFail($wilayaId);

        $sites = Site::withCount('endroits')
            ->where('wilaya_id', $wilayaId)
            ->get();

        return response()->json([
            'wilaya' => $wilaya,
            'sites' => $sites,
        ]);
    }

    public function site(int $id): JsonResponse
    {
        $site = Site::with(['endroits', 'wilaya'])->findOrFail($id);

        return response()->json($site);
    }

    public function siteEndroits(int $id): JsonResponse
    {
        $site = Site::findOrFail($id);
        $endroits = Endroit::withCount('objets')
            ->where('site_id', $id)
            ->where('is_published', true)
            ->get();

        return response()->json([
            'site' => $site,
            'endroits' => $endroits,
        ]);
    }

    public function endroit(int $id): JsonResponse
    {
        $endroit = Endroit::with(['site', 'objets' => function ($q) {
            $q->where('is_published', true);
        }])->where('is_published', true)->findOrFail($id);

        return response()->json($endroit);
    }

    public function endroitObjets(int $id): JsonResponse
    {
        $endroit = Endroit::with('site')->findOrFail($id);
        $objets = Objet::where('endroit_id', $id)->where('is_published', true)->get();

        return response()->json([
            'endroit' => $endroit,
            'objets' => $objets,
        ]);
    }

    public function objet(int $id): JsonResponse
    {
        $objet = Objet::with(['endroit.site'])->where('is_published', true)->findOrFail($id);

        return response()->json($objet);
    }

    public function resolveQr(Request $request, string $qrCodeId): JsonResponse
    {
        $qr = QrCode::with(['site.wilaya', 'endroit', 'objet' => function ($q) {
            $q->with('endroit');
        }])->where('qr_code_id', $qrCodeId)->first();

        if (!$qr) {
            return response()->json(['message' => 'QR code non reconnu.'], 404);
        }

        $deviceId = $request->input('device_id');
        $ipAddress = $request->ip();

        if ($deviceId) {
            $visitor = \App\Models\Visitor::firstOrCreate(
                ['device_id' => $deviceId],
                [
                    'total_scans' => 0,
                    'is_blocked' => false,
                ]
            );

            $updateData = ['last_seen_at' => now()];

            if (!$visitor->ip_address) {
                $updateData['ip_address'] = $ipAddress;
                $geo = GeoService::resolve($ipAddress);
                $updateData = array_merge($updateData, $geo);
            }

            $visitor->update($updateData);

            $isUnique = $visitor->total_scans === 0;
            $visitor->increment('total_scans');

            $qr->site->increment('total_visits');
            if ($isUnique) {
                $qr->site->increment('unique_visitors');
            }

            if ($qr->type === 'endroit' && $qr->endroit) {
                $qr->endroit->increment('total_visits');
                if ($isUnique) {
                    $qr->endroit->increment('unique_visitors');
                }
            }

            if ($qr->type === 'objet' && $qr->objet) {
                $qr->objet->increment('total_visits');
                if ($isUnique) {
                    $qr->objet->increment('unique_visitors');
                }
            }

            \App\Models\ScanLog::create([
                'visitor_id' => $visitor->id,
                'site_id' => $qr->site_id,
                'endroit_id' => $qr->endroit_id,
                'qr_code_id' => $qrCodeId,
                'action' => 'scan',
                'device_info' => $request->userAgent(),
                'ip_address' => $ipAddress,
            ]);
        }

        return response()->json([
            'type' => $qr->type,
            'site' => $qr->site,
            'endroit' => $qr->endroit,
            'objet' => $qr->objet,
        ]);
    }

    public function packVersion(int $siteId): JsonResponse
    {
        $site = Site::findOrFail($siteId);

        return response()->json([
            'site_id' => $site->id,
            'version' => $site->version_pack,
            'hash' => $site->pack_hash,
        ]);
    }

    public function packDownload(int $siteId): JsonResponse
    {
        $site = Site::with(['endroits', 'qrCodes'])->findOrFail($siteId);

        return response()->json([
            'site' => $site->name,
            'version' => $site->version_pack,
            'endroits_count' => $site->endroits()->count(),
            'message' => 'Pack download endpoint - à connecter au stockage du pack compilé',
        ]);
    }
}

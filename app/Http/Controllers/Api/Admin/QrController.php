<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Traits\AuthorizesSite;
use App\Http\Controllers\Api\Controller;
use App\Models\QrCode;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrController extends Controller
{
    use AuthorizesSite;

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:site,endroit',
            'site_id' => 'required|exists:sites,id',
            'endroit_id' => 'nullable|exists:endroits,id',
        ]);

        $site = Site::findOrFail($request->site_id);
        $this->authorizeSite($request, $site);

        $qrCodeId = match ($request->type) {
            'site' => 'SITE-' . str_pad($request->site_id, 4, '0', STR_PAD_LEFT),
            'endroit' => 'END-' . str_pad($request->endroit_id, 4, '0', STR_PAD_LEFT),
        };

        $qr = QrCode::updateOrCreate(
            ['qr_code_id' => $qrCodeId],
            [
                'type' => $request->type,
                'site_id' => $request->site_id,
                'endroit_id' => $request->endroit_id,
            ]
        );

        return response()->json($qr);
    }

    public function regenerate(Request $request, string $qrCodeId): JsonResponse
    {
        $qr = QrCode::with('site')->where('qr_code_id', $qrCodeId)->firstOrFail();
        $this->authorizeSite($request, $qr->site);

        $newId = $qr->qr_code_id . '-' . Str::random(4);
        $qr->update(['qr_code_id' => $newId]);

        return response()->json($qr->fresh());
    }

    public function exportPdf(Request $request, int $siteId): JsonResponse
    {
        $site = Site::with('qrCodes')->findOrFail($siteId);
        $this->authorizeSite($request, $site);

        return response()->json([
            'site' => $site->name,
            'qr_codes' => $site->qrCodes,
            'message' => 'PDF export placeholder - à implémenter avec dompdf/browsershot',
        ]);
    }
}

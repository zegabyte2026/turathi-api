<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrCode extends Model
{
    protected $fillable = [
        'qr_code_id',
        'type',
        'site_id',
        'endroit_id',
        'objet_id',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function endroit(): BelongsTo
    {
        return $this->belongsTo(Endroit::class);
    }

    public function objet(): BelongsTo
    {
        return $this->belongsTo(Objet::class);
    }
}

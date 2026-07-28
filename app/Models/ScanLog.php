<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanLog extends Model
{
    protected $fillable = [
        'visitor_id',
        'site_id',
        'endroit_id',
        'qr_code_id',
        'action',
        'device_info',
        'ip_address',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function endroit(): BelongsTo
    {
        return $this->belongsTo(Endroit::class);
    }
}

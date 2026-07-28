<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackVersion extends Model
{
    protected $fillable = [
        'site_id',
        'version',
        'hash',
        'status',
        'endroits_count',
        'images_count',
        'audios_count',
        'size_bytes',
        'compiled_at',
    ];

    protected $casts = [
        'compiled_at' => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}

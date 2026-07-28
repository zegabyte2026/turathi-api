<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Objet extends Model
{
    use HasFactory;

    protected $fillable = [
        'endroit_id',
        'title',
        'description',
        'images',
        'audio_paths',
        'qr_code_id',
        'materiau',
        'periode',
        'dimensions',
        'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'images' => 'array',
        'audio_paths' => 'array',
        'is_published' => 'boolean',
    ];

    public function endroit(): BelongsTo
    {
        return $this->belongsTo(Endroit::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'objet_id');
    }
}

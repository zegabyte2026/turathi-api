<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endroit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'title',
        'description',
        'latitude',
        'longitude',
        'altitude',
        'images',
        'audio_paths',
        'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'images' => 'array',
        'audio_paths' => 'array',
        'is_published' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function objets(): HasMany
    {
        return $this->hasMany(Objet::class);
    }
}

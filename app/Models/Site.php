<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wilaya_id',
        'name',
        'description',
        'cover_image',
        'audio_paths',
        'images',
        'latitude',
        'longitude',
        'altitude',
        'is_published',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'audio_paths' => 'array',
        'images' => 'array',
        'is_published' => 'boolean',
    ];

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function endroits(): HasMany
    {
        return $this->hasMany(Endroit::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_site');
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function packVersions(): HasMany
    {
        return $this->hasMany(PackVersion::class);
    }
}

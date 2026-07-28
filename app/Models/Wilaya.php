<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cover_image',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}

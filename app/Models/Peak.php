<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peak extends Model
{
    /** @use HasFactory<\Database\Factories\PeakFactory> */
    use HasFactory;

    public function ascents(): HasMany
    {
        return $this->hasMany(Ascent::class);
    }
}

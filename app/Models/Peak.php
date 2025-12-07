<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Peak extends Model
{
    /** @use HasFactory<\Database\Factories\PeakFactory> */
    use HasFactory;

    public function ascents(): HasMany
    {
        return $this->hasMany(Ascent::class);
    }

    /**
     * Scope a query to search by name or category using LIKE.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('category', 'like', "%{$term}%");
        });
    }

    /**
     * Scope a query to filter by exact category value.
     */
    public function scopeFilterCategory(Builder $query, ?string $category): Builder
    {
        $category = trim((string) $category);

        if ($category === '') {
            return $query;
        }

        return $query->where('category', $category);
    }
}

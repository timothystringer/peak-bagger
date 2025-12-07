<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ascent extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\AscentFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'peak_id',
        'date',
        'notes',
    ];

    public function peak(): BelongsTo
    {
        return $this->belongsTo(Peak::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pictures')
            ->useDisk('s3')
            ->singleFile(false);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 300, 300)
            ->queued();

        $this->addMediaConversion('preview')
            ->width(1200)
            ->keepOriginalImageFormat()
            ->queued();
    }
}

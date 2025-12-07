<?php

use App\Models\Peak;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

it('allows the ascent owner to delete a media item', function () {
    Storage::fake('s3');

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'media' => [UploadedFile::fake()->image('photo.jpg')],
        ])
        ->assertStatus(302);

    $media = SpatieMedia::first();
    expect($media)->not->toBeNull();

    $this->actingAs($user)
        ->delete(route('media.destroy', $media))
        ->assertStatus(302);

    expect(SpatieMedia::count())->toBe(0);
});

it('prevents other users from deleting someone else\'s media', function () {
    Storage::fake('s3');

    $owner = User::factory()->create();
    $other = User::factory()->create();
    $peak = Peak::factory()->create();

    $this->actingAs($owner)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'media' => [UploadedFile::fake()->image('photo.jpg')],
        ])
        ->assertStatus(302);

    $media = SpatieMedia::first();
    expect($media)->not->toBeNull();

    $this->actingAs($other)
        ->delete(route('media.destroy', $media))
        ->assertStatus(403);

    expect(SpatieMedia::count())->toBe(1);
});


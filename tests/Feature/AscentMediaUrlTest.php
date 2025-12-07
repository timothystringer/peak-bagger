<?php

use App\Models\Peak;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

it('returns a non-empty media url for conversions', function () {
    Storage::fake('s3');

    config(['filesystems.disks.s3.url' => 'https://d111example.cloudfront.net']);

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $file = UploadedFile::fake()->image('photo1.jpg');

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'notes' => 'Climbed',
            'media' => [$file],
        ])
        ->assertStatus(302);

    $media = SpatieMedia::first();

    expect($media)->not->toBeNull();

    $url = $media->getUrl('thumb');

    expect(is_string($url))->toBeTrue();
    expect($url)->not->toBeEmpty();
});

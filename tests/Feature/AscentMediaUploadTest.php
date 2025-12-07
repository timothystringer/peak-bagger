<?php

use App\Models\Peak;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

it('allows an authenticated user to create an ascent with media', function () {
    Storage::fake('s3');

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'notes' => 'Nice climb',
            'media' => $files,
        ])
        ->assertStatus(302);

    $this->assertDatabaseHas('ascents', [
        'user_id' => $user->id,
        'peak_id' => $peak->id,
    ]);

    // There should be at least one media item attached
    $mediaCount = SpatieMedia::count();
    expect($mediaCount)->toBeGreaterThanOrEqual(1);

    // The fake s3 disk should have files stored
    $storedFiles = Storage::disk('s3')->allFiles();
    expect(count($storedFiles))->toBeGreaterThanOrEqual(1);
});

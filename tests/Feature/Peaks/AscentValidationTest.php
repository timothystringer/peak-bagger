<?php

use App\Models\Peak;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('rejects more than 10 uploaded files', function () {
    Storage::fake('s3');

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $files = [];
    for ($i = 0; $i < 11; $i++) {
        $files[] = UploadedFile::fake()->image("photo{$i}.jpg");
    }

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'media' => $files,
        ])
        ->assertSessionHasErrors(['media']);
});

it('rejects invalid file types', function () {
    Storage::fake('s3');

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'media' => [$file],
        ])
        ->assertSessionHasErrors(['media.0']);
});

it('rejects files larger than 5MB', function () {
    Storage::fake('s3');

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    // create a fake file slightly larger than 5MB (5121 KB)
    $file = UploadedFile::fake()->create('big.jpg', 5121, 'image/jpeg');

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'media' => [$file],
        ])
        ->assertSessionHasErrors(['media.0']);
});

<?php

use App\Models\Peak;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;

it('queues conversion jobs when media is uploaded', function () {
    Storage::fake('s3');
    Queue::fake();

    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $files = [UploadedFile::fake()->image('photo1.jpg')];

    $this->actingAs($user)
        ->post(route('peaks.ascents.store', ['peak' => $peak->id]), [
            'date' => now()->toDateString(),
            'notes' => 'Climbed',
            'media' => $files,
        ])
        ->assertStatus(302);

    // At least one PerformConversionsJob should have been pushed
    Queue::assertPushed(PerformConversionsJob::class);
});

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\Jobs\PerformConversions;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RegenerateMediaConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:regenerate {mediaId?} {--all : Regenerate conversions for all media}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate media conversions for a single media item or all media records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $mediaId = $this->argument('mediaId');

        if ($this->option('all')) {
            $this->info('Dispatching conversion jobs for all media...');

            $chunkSize = 100;
            Media::chunk($chunkSize, function ($items) {
                foreach ($items as $media) {
                    PerformConversions::dispatch($media)->onQueue('default');
                }
                // brief pause to avoid queue storms
                usleep(250000);
            });

            $this->info('Dispatched conversions for all media.');

            return Command::SUCCESS;
        }

        if ($mediaId) {
            $media = Media::find($mediaId);

            if (! $media) {
                $this->error("Media with ID {$mediaId} not found.");

                return Command::FAILURE;
            }

            PerformConversions::dispatch($media)->onQueue('default');

            $this->info("Dispatched conversion job for media ID {$mediaId}.");

            return Command::SUCCESS;
        }

        $this->error('Please provide a mediaId or use the --all flag.');

        return Command::INVALID;
    }
}

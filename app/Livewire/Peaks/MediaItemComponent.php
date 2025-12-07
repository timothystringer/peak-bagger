<?php

namespace App\Livewire\Peaks;

use Illuminate\Support\Facades\Gate;
use Livewire\Volt\Component as VoltComponent;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaItemComponent extends VoltComponent
{
    public Media $media;

    public bool $confirming = false;

    public bool $deleted = false;

    public function mount(Media $media): void
    {
        $this->media = $media;
    }

    public function confirmDelete(): void
    {
        $this->confirming = true;
    }

    public function delete(): void
    {
        $model = $this->media->model;

        if (! $model) {
            abort(404);
        }

        if (Gate::denies('delete', $model)) {
            abort(403);
        }

        $mediaId = $this->media->id;

        $this->media->delete();

        // notify the frontend so it can animate and show a toast
        $this->dispatchBrowserEvent('mediaDeleted', ['id' => $mediaId]);

        $this->deleted = true;
        $this->confirming = false;
    }
}

<?php

namespace App\Livewire\Peaks;

use App\Models\Ascent;
use App\Models\Peak;
use Illuminate\Validation\Rule;
use Livewire\Files\TemporaryUploadedFile;
use Livewire\Volt\Component as VoltComponent;

class UploaderComponent extends VoltComponent
{
    public Peak $peak;

    /** @var array<int, \Livewire\Files\TemporaryUploadedFile>|array */
    public array $files = [];

    public ?string $date = null;

    public ?string $notes = null;

    protected array $rules = [
        'date' => ['required', 'date'],
        'notes' => ['nullable', 'string'],
        'files' => ['nullable', 'array', 'max:10'],
        'files.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
    ];

    public function mount(Peak $peak): void
    {
        $this->peak = $peak;
        $this->date = now()->toDateString();
    }

    public function upload(): \Illuminate\Http\RedirectResponse
    {
        $this->validate();

        $ascent = Ascent::create([
            'user_id' => auth()->id(),
            'peak_id' => $this->peak->id,
            'date' => $this->date,
            'notes' => $this->notes,
        ]);

        foreach ($this->files as $file) {
            if ($file instanceof TemporaryUploadedFile) {
                // addMedia accepts a path; getRealPath() is available on the uploaded file
                $ascent->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('pictures');

                // cleanup the temporary file if necessary
                $file->delete();
            }
        }

        // reset state
        $this->files = [];
        $this->notes = null;

        return redirect()->to(route('peaks.show', ['peak' => $this->peak->id]));
    }
}

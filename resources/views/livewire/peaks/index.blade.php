<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-layouts.app :title="__('Peaks')">
    <div class="p-4 rounded bg-white dark:bg-neutral-900 border">
        <h1 class="text-xl font-semibold">Peaks</h1>
        <div class="mt-4 grid gap-4">
            @foreach ($peaks as $peak)
                <div class="p-3 border rounded flex justify-between items-center">
                    <div>
                        <a href="{{ route('peaks.show', $peak) }}" class="font-medium">{{ $peak->name }}</a>
                        <div class="text-sm text-muted">{{ $peak->category }} • {{ $peak->elevation ?? 'n/a' }}</div>
                    </div>
                    <div class="text-sm text-muted">Ascents: {{ $peak->ascents_count }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $peaks->links() }}</div>
    </div>
</x-layouts.app>

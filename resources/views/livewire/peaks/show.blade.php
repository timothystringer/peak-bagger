<x-layouts.app :title="$peak->name">
    <div class="space-y-6">
        <div class="p-4 rounded bg-white dark:bg-neutral-900 border">
            <h1 class="text-xl font-semibold">{{ $peak->name }}</h1>
            <p class="text-sm text-muted">Category: {{ $peak->category }} • Elevation: {{ $peak->elevation ?? 'n/a' }}</p>
        </div>

        <div class="p-4 rounded bg-white dark:bg-neutral-900 border">
            <h2 class="text-lg font-medium">Add an Ascent</h2>
            <form method="POST" action="{{ route('peaks.ascents.store', ['peak' => $peak->id]) }}" enctype="multipart/form-data" id="ascent-form">
                @csrf
                <div class="grid gap-2">
                    <flux:input label="Date" type="date" name="date" value="{{ old('date', now()->toDateString()) }}" />
                    <flux:textarea label="Notes" name="notes">{{ old('notes') }}</flux:textarea>

                    {{-- Volt uploader component --}}
                    @include('livewire.peaks.uploader', ['peak' => $peak])

                    <noscript>
                        <input type="file" name="media[]" multiple accept="image/*" />
                        <flux:button
                            type="submit"
                            variant="primary"
                            class="inline-flex items-center px-3 py-1.5 rounded text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                        >
                            Add Ascent
                        </flux:button>
                    </noscript>
                </div>
            </form>
        </div>

        <div class="p-4 rounded bg-white dark:bg-neutral-900 border">
            <h2 class="text-lg font-medium">Ascents</h2>
            <div class="grid gap-4">
                @foreach ($ascents as $ascent)
                    <div class="border rounded p-3 flex gap-4" id="ascent-{{ $ascent->id }}">
                        <div class="flex-1">
                            <div class="text-sm text-muted">{{ \Illuminate\Support\Carbon::parse($ascent->date)->toDateString() }} by {{ $ascent->user->name }}</div>
                            <div class="mt-2">{{ $ascent->notes }}</div>
                            <div class="mt-2 flex gap-2 items-start">
                                @foreach ($ascent->getMedia('pictures') as $media)
                                    @include('livewire.peaks._media_item', ['media' => $media])
                                @endforeach
                            </div>
                        </div>

                        @if (auth()->check() && auth()->user()->can('delete', $ascent))
                            <div class="flex items-start">
                                <button
                                    type="button"
                                    class="ascent-delete inline-flex items-center px-2 py-1 rounded text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition"
                                    data-peak-id="{{ $peak->id }}"
                                    data-ascent-id="{{ $ascent->id }}"
                                >
                                    Delete
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.ascent-delete');
        if (! btn) return;

        if (! confirm('Delete this ascent? This cannot be undone.')) return;

        const peakId = btn.dataset.peakId;
        const ascentId = btn.dataset.ascentId;

        fetch(`/peaks/${peakId}/ascents/${ascentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        }).then(res => {
            if (! res.ok) throw new Error('Delete failed');
            // 204 No Content will have no body — treat as success
            return res.status === 204 ? null : res.json();
        }).then(() => {
            const el = document.getElementById(`ascent-${ascentId}`);
            if (el) el.remove();
        }).catch(err => {
            alert('Unable to delete ascent');
            console.error(err);
        });
    });
    </script>
    @endpush
</x-layouts.app>

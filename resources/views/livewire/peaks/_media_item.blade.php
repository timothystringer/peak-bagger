@volt
<?php
new \App\Livewire\Peaks\MediaItemComponent($media);
?>

<div id="media-<?= $this->media->id ?>-wrapper">
    <?php if ($this->deleted): ?>
        <!-- Deleted; nothing to show -->
    <?php else: ?>
        <div class="relative media-thumb" id="media-<?= $this->media->id ?>">
            <img src="<?= $this->media->getUrl('thumb') ?>" alt="media" class="w-20 h-20 object-cover rounded" />

            <?php if (Gate::check('delete', $this->media->model)): ?>
                <!-- Delete trigger -->
                <button wire:click="confirmDelete" class="absolute top-0 right-0 bg-red-500 text-white rounded px-1 text-xs">Delete</button>

                <!-- Confirmation modal (Flux modal) -->
                <?php if ($this->confirming): ?>
                    <flux:modal open="true" :showClose="true">
                        <div class="p-4">
                            <h3 class="text-lg font-medium">Delete image</h3>
                            <p class="text-sm text-muted mt-2">Are you sure you want to permanently delete this image? This action cannot be undone.</p>

                            <div class="mt-4 flex gap-2">
                                <flux:button wire:click="delete" wire:loading.attr="disabled" variant="danger">
                                    <span wire:loading.remove>Confirm delete</span>
                                    <span wire:loading>Deleting...</span>
                                </flux:button>

                                <flux:button wire:click="confirmDelete" variant="secondary">Cancel</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('mediaDeleted', function (e) {
        const id = e.detail.id;
        const el = document.getElementById('media-' + id);
        if (el) {
            el.style.transition = 'opacity 300ms ease, transform 300ms ease';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.95)';
            setTimeout(() => {
                const wrapper = document.getElementById('media-' + id + '-wrapper');
                if (wrapper) wrapper.remove();
            }, 320);
        }

        // Show a simple Flux-styled toast using a div (fallback if Flux toast not available)
        try {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow';
            toast.textContent = 'Image deleted';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        } catch (err) {
            console.log('mediaDeleted', e.detail);
        }
    });
});
</script>
@endpush
@endvolt

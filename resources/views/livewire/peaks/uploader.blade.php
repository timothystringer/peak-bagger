<div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Date</label>
        <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="flux:input" />
    </div>

    <div class="mt-2">
        <label class="block text-sm font-medium text-gray-700">Notes</label>
        <textarea name="notes" class="flux:textarea">{{ old('notes') }}</textarea>
    </div>

    <div class="mt-2">
        <label class="block text-sm font-medium text-gray-700">Photos</label>
        <input id="uploader-input" type="file" name="media[]" multiple accept="image/*" />

        {{-- Preview handled by JS / backend component; not in this view --}}
        <div class="mt-2 flex gap-2" id="uploader-previews"></div>
    </div>

    <div class="mt-4">
        <flux:button type="submit" variant="primary">Add Ascent</flux:button>
    </div>
</div>

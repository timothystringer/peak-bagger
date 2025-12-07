@volt
<?php
use App\Models\Peak;
use function Livewire\Volt\{state, computed};

state([
    'q' => request('q', ''),
    'category' => request('category', ''),
    'perPage' => 10,
    'page' => request('page', 1),
]);

$categories = computed(fn () => Peak::query()->select('category')->distinct()->orderBy('category')->pluck('category'));

$peaks = computed(function () {
    $query = Peak::withCount('ascents')
        ->search($this->q)
        ->filterCategory($this->category)
        ->orderBy('name');

    return $query->paginate($this->perPage, ['*'], 'page', $this->page);
});

// suggestions (typeahead) - top 5 matches by name
$suggestions = computed(fn () => $this->q ? Peak::where('name', 'like', "%{$this->q}%")->orderBy('name')->limit(5)->pluck('name') : collect());

$clear = fn () => ($this->q = '') && ($this->category = '');

$syncUrl = fn () => (
    function () use ($this) {
        $params = [];
        if ($this->q) $params['q'] = $this->q;
        if ($this->category) $params['category'] = $this->category;
        if ($this->page && $this->page > 1) $params['page'] = $this->page;

        $url = url()->current();
        if (count($params)) {
            $url .= '?'.http_build_query($params);
        }

        // push state so sharing current filters works
        echo "<script>history.replaceState({}, '', '".addslashes($url)."');</script>";
    }
)();
?>

<div class="p-4 rounded bg-white dark:bg-neutral-900 border">
    <div class="grid gap-3 md:grid-cols-3">
        <div>
            <flux:input wire:model.live.debounce.300ms="q" label="Search" placeholder="Search peaks or categories" />
            <!-- suggestions -->
            <?php if ($this->suggestions->isNotEmpty()): ?>
                <ul class="mt-1 bg-white border rounded shadow-sm max-h-40 overflow-auto">
                    <?php foreach ($this->suggestions as $s): ?>
                        <li class="px-3 py-1 cursor-pointer hover:bg-gray-100" wire:click="$set('q', '<?php echo addslashes($s); ?>')"><?php echo e($s); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Category</label>
            <select wire:model="category" class="mt-1 block w-full rounded border px-3 py-2">
                <option value="">All categories</option>
                <?php foreach ($this->categories as $c): ?>
                    <option value="<?php echo e($c); ?>"><?php echo e($c); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-end justify-end">
            <flux:button wire:click="$call('clear')" variant="secondary">Clear</flux:button>
        </div>
    </div>

    <div class="mt-4">
        <div class="grid gap-4">
            <?php foreach ($this->peaks as $peak): ?>
                <div class="p-3 border rounded flex justify-between items-center">
                    <div>
                        <a href="<?php echo e(route('peaks.show', $peak)); ?>" class="font-semibold text-blue-600"><?php echo e($peak->name); ?></a>
                        <div class="text-sm text-muted"><?php echo e($peak->category); ?> • Ascents: <?php echo e($peak->ascents_count); ?></div>
                    </div>
                    <div>
                        <a href="<?php echo e(route('peaks.show', $peak)); ?>" class="text-sm text-blue-500">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <?php echo $this->peaks->withQueryString()->links(); ?>
        </div>
    </div>
</div>
@endvolt


<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAscentRequest;
use App\Models\Ascent;
use App\Models\Peak;
use Illuminate\Http\RedirectResponse;

class AscentController extends Controller
{
    public function store(StoreAscentRequest $request, Peak $peak): RedirectResponse
    {
        $validated = $request->validated();

        $ascent = Ascent::create([
            'user_id' => auth()->id(),
            'peak_id' => $peak->id,
            'date' => $validated['date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($request->file('media', []) as $file) {
            $ascent->addMedia($file)->toMediaCollection('pictures');
        }

        return redirect()->back();
    }

    public function destroy(Peak $peak, Ascent $ascent)
    {
        // ensure the ascent belongs to the provided peak
        if ($ascent->peak_id !== $peak->id) {
            abort(404);
        }

        $this->authorize('delete', $ascent);

        // delete associated media via Spatie Media Library, but protect against storage errors in tests
        try {
            $ascent->clearMediaCollection('pictures');
        } catch (\Exception $e) {
            // log and continue — clearing media should not prevent deletion
            \Log::warning('Failed to clear media for Ascent '.$ascent->id.': '.$e->getMessage());
        }

        $ascent->delete();

        // Return a 204 No Content response for DELETE requests (suitable for AJAX and tests)
        return response()->noContent();
    }
}

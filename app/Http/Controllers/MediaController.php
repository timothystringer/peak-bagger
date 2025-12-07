<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function destroy(Request $request, Media $media): RedirectResponse
    {
        $model = $media->model; // the parent model, e.g., Ascent

        if (! $model) {
            abort(404);
        }

        // authorize using the Ascent policy if model is an Ascent
        if ($model instanceof \App\Models\Ascent) {
            if (Gate::denies('delete', $model)) {
                abort(403);
            }
        } else {
            // default deny for other models
            abort(403);
        }

        $media->delete();

        return redirect()->back();
    }
}


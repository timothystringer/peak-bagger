<?php

namespace App\Http\Controllers;

use App\Models\Peak;

class PeaksController extends Controller
{
    public function index()
    {
        $peaks = Peak::withCount('ascents')->orderBy('name')->paginate(20);

        return view('livewire.peaks.index', compact('peaks'));
    }

    public function show(Peak $peak)
    {
        $ascents = $peak->ascents()->with(['user', 'media'])->latest()->get();

        return view('livewire.peaks.show', compact('peak', 'ascents'));
    }
}

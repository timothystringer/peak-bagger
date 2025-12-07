<?php

namespace App\Http\Controllers;

use App\Models\Peak;
use Illuminate\Http\Request;

class PeaksController extends Controller
{
    public function index(Request $request)
    {
        $query = Peak::query();

        if ($q = $request->query('q')) {
            // simple LIKE search on peak name
            $query->where('name', 'like', "%{$q}%");
        }

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        $peaks = $query->withCount('ascents')->orderBy('name')->paginate(20)->withQueryString();

        return view('livewire.peaks.index', compact('peaks'));
    }

    public function show(Peak $peak)
    {
        $ascents = $peak->ascents()->with(['user', 'media'])->latest()->get();

        return view('livewire.peaks.show', compact('peak', 'ascents'));
    }
}

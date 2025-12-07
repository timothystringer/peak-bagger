<?php

namespace App\Http\Controllers;

use App\Models\Peak;
use Illuminate\Http\JsonResponse;

class UploaderController extends Controller
{
    public function config(Peak $peak): JsonResponse
    {
        // Centralize uploader rules here so the frontend stays dumb
        $config = [
            'maxFiles' => 10,
            // max file size in kilobytes (matching validation rule max:5120)
            'maxFileSizeKb' => 5120,
            'acceptedMimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
            'uploadUrl' => route('peaks.ascents.store', ['peak' => $peak->id]),
        ];

        return response()->json($config);
    }
}


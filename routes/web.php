<?php

use App\Http\Controllers\AscentController;
use App\Http\Controllers\PeaksController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UploaderController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('peaks', [PeaksController::class, 'index'])->middleware(['auth'])->name('peaks.index');
Route::get('peaks/{peak}', [PeaksController::class, 'show'])->middleware(['auth'])->name('peaks.show');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::post('peaks/{peak}/ascents', [AscentController::class, 'store'])
        ->name('peaks.ascents.store');

    Route::delete('peaks/{peak}/ascents/{ascent}', [AscentController::class, 'destroy'])
        ->name('peaks.ascents.destroy');

    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('peaks/{peak}/uploader-config', [UploaderController::class, 'config'])->name('peaks.uploader.config');
});

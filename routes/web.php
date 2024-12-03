<?php

use App\Http\Controllers\ProfileController;
use App\Models\Alert;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/alerts', function (Request $request) {
    $request->validate([
        'coin_name' => 'required|string',
        'alert_value' => 'required|numeric',
    ]);

    Alert::create([
        'user_id' => auth()->id(),
        'coin_name' => $request->coin_name,
        'alert_value' => $request->alert_value,
    ]);

    return response()->json(['message' => 'Alerta salvo com sucesso!']);
});

require __DIR__.'/auth.php';
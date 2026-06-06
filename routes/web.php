<?php

use Illuminate\Support\Facades\Route;

// SPA catch-all: todas las rutas del frontend las maneja Vue Router
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
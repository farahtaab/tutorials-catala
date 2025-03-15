<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportIfixitController;
use App\Http\Controllers\GuideController;

Route::get('/', [GuideController::class, 'index']);
Route::get('/guide/{id}', [GuideController::class, 'show']);
Route::get('/import-ifixit', ImportIfixitController::class);
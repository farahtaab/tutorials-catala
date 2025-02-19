<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportIfixitController;
use App\Http\Controllers\GuideController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import-ifixit', ImportIfixitController::class);

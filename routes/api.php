<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EngagementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// CHANGED: Added the ->name(...) part at the end
Route::middleware('auth:sanctum')->post('/track-engagement', [EngagementController::class, 'store'])->name('api.track-engagement');
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BpmController;
use App\Http\Controllers\Api\BpmSingleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\BuffplotController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::apiResource('/bpm', BpmController::class);
Route::apiResource('/bpm-single', BpmSingleController::class);

Route::post('/buffplot', [BuffplotController::class, 'store']);
Route::get('/buffplots', [BuffplotController::class, 'index']);

<?php

use App\Http\Controllers\Api\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("v1")->group(function () {
    Route::get("documents", [DocumentController::class, 'index']);
    Route::get("document/{id}", [DocumentController::class, 'document']);
    Route::post("document/{id}", [DocumentController::class, 'create']);
});

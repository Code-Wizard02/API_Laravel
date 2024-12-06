<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(
    [
        'middleware' => 'auth:sanctum'
    ],
    function () {
        Route::get('userProfile', [AuthController::class, 'userProfile']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('userResource', [AuthController::class, 'userResource']);
        Route::get('userResourceCollection', [AuthController::class, 'userResourceCollection']);
        Route::get('userResourceCollectionWithMeta', [AuthController::class, 'userResourceCollectionWithMeta']);
    }
);

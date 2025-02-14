<?php

use App\Http\Controllers\AppTopCategoryController;
use App\Http\Controllers\AppTopController;
use App\Http\Middleware\LogRequests;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:5,1', LogRequests::class])->group(function () {
    Route::get('/appTopCategory', [AppTopController::class, 'getTopCategory']);
    Route::get('/addTopCategory', [AppTopController::class, 'addTopCategory']);
});

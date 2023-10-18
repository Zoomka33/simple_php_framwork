<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\💩;
use Kalinin\Framework\Http\Response;
use Kalinin\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/crap', [💩::class, '👨🏿']),
    Route::get('/posts/{id: \d+}', [PostController::class, 'show']),
    Route::get('/posts/create', [PostController::class, 'showCreate']),
    Route::post('/posts', [PostController::class, 'create']),
    Route::get('/hi/{name}', function ($name) {
        return new Response("Hello $name");
    }),
];

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('login', [ AuthController::class, 'login' ]);
    Route::post('signup', [ AuthController::class, 'signup' ]);
    Route::post('logout', [ AuthController::class, 'logout' ]);
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

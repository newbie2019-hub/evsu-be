<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::group(['prefix' => 'admin'], function (){
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::post('update', [AdminAuthController::class, 'update']);
        Route::post('change_password', [AdminAuthController::class, 'changePassword']);
        Route::post('me', [AdminAuthController::class, 'me']);
    });

    
    Route::group(['prefix' => 'user'], function () {
        Route::post('login', [UserAuthController::class, 'login']);    
        Route::post('me', [UserAuthController::class, 'me']);        
        Route::post('update', [UserAuthController::class, 'update']);
        Route::post('logout', [UserAuthController::class, 'logout']);            
    });
});

Route::group(['prefix' => 'admin'], function (){
    Route::apiResource('post', PostController::class);
    Route::apiResource('applicant', ApplicantController::class);
    Route::get('post/view/{id}', [PostController::class, 'view']);
});

Route::group(['prefix' => 'user'], function (){
    Route::get('/download/{file}', [ApplicantController::class, 'download']);
    Route::apiResource('post', UserPostController::class);
    Route::get('post/view/{id}', [UserPostController::class, 'view']);
    Route::post('upload-files', [ApplicantController::class, 'upload']);
});

<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserPostController;
use App\Models\ActivityLog;
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
        Route::get('logs', [ActivityLogController::class, 'adminLogs']);
        Route::get('accounts', [AdminAuthController::class, 'accounts']);
        Route::post('change_password', [AdminAuthController::class, 'changePassword']);
        Route::post('me', [AdminAuthController::class, 'me']);
    });
    
    
    Route::group(['prefix' => 'user'], function () {
        Route::post('login', [UserAuthController::class, 'login']);    
        Route::post('me', [UserAuthController::class, 'me']);       
        Route::get('logs', [ActivityLogController::class, 'userLogs']);
        Route::post('store', [UserAuthController::class, 'store']);        
        Route::post('update', [UserAuthController::class, 'update']);
        Route::post('logout', [UserAuthController::class, 'logout']);            
    });
});

Route::group(['prefix' => 'admin'], function (){
    Route::apiResource('post', PostController::class);
    Route::get('records/export', [ApplicantController::class, 'export']);
    Route::get('accounts/export', [AdminAuthController::class, 'export']);
    Route::put('account/approve/{id}', [UserAuthController::class, 'approve']);
    Route::delete('account/{id}', [UserAuthController::class, 'destroy']);
    Route::put('applicant/move/{id}', [ApplicantController::class, 'moveApplicant']);
    Route::apiResource('applicant', ApplicantController::class);
    Route::get('post/view/{id}', [PostController::class, 'view']);
    Route::get('summary', [AdminAuthController::class, 'dashboard']);
});

Route::group(['prefix' => 'user'], function (){
    Route::post('view/post/{id}', [UserPostController::class, 'view']);
    Route::get('/download/{file}', [ApplicantController::class, 'download']);
    Route::apiResource('post', UserPostController::class);
    Route::get('post/view/{id}', [UserPostController::class, 'view']);
    Route::post('applicant/email/verify', [ApplicantController::class, 'verifyEmail']);
    Route::post('email/verify', [UserAuthController::class, 'verifyEmail']);
    Route::post('upload-files', [ApplicantController::class, 'upload']);
    Route::post('upload-files/profile', [ApplicantController::class, 'profileImage']);
    Route::post('upload-files/tes', [ApplicantController::class, 'uploadTESList']);
    Route::post('upload-files/image', [ApplicantController::class, 'uploadImage']);
});

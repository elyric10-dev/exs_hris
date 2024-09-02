<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\Api\UserRoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartmentController;

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

// TESTING API
Route::get('/testing', [TestingController::class, 'index']);

// AUTHENTICATION
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/refresh', [AuthenticationController::class, 'refresh']);
});

// USER ROLES API RESOURCE
Route::apiResource('user-roles', UserRoleController::class)->middleware('auth:sanctum');

//USERS API RESOURCE
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');

// DEPARTMENTS API RESOURCE
Route::apiResource('departments', DepartmentController::class)->middleware('auth:sanctum');

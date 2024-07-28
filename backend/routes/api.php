<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskPriorityController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterUserController::class, 'store']);
Route::post('login', [LoginUserController::class, 'store']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('task-categories', TaskCategoryController::class);
    Route::apiResource('task-priorities', TaskPriorityController::class);
    Route::apiResource('task-statuses', TaskStatusController::class);
});

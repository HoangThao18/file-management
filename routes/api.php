<?php

use App\Http\Controllers\api\FileController;
use App\Http\Controllers\api\FolderController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\RegisterController;
use App\Http\Controllers\api\SupportController;
use App\Http\Controllers\api\TrashController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {

  Route::get('/user/{user}', [UserController::class, 'show']);
  Route::get('/user', [UserController::class, 'index']);
  Route::put('/user/{user}', [UserController::class, 'update']);
  Route::delete('/user/{user}', [UserController::class, 'destroy']);
  Route::post('/user', [UserController::class, 'store']);

  Route::post('/folder', [FolderController::class, 'store']);
  Route::get('/folder/{folder}', [FolderController::class, 'show']);
  Route::get('/folder', [FolderController::class, 'index']);
  Route::put('/folder/{folder}', [FolderController::class, 'update']);
  Route::delete('/folder/{folder}', [FolderController::class, 'destroy']);

  Route::get('/file/{file}', [FileController::class, 'show']);
  Route::get('/file', [FileController::class, 'index']);
  Route::put('/file/{file}', [FileController::class, 'update']);
  Route::delete('/file/{file}', [FileController::class, 'destroy']);
  Route::post('/file', [FileController::class, 'store']);

  Route::get('/trash/{trash}', [TrashController::class, 'show']);
  Route::get('/trash', [TrashController::class, 'index']);
  Route::put('/trash/{trash}', [TrashController::class, 'update']);
  Route::delete('/trash/{trash}', [TrashController::class, 'destroy']);
  Route::post('/trash', [TrashController::class, 'store']);

  Route::get('/support/{support}', [SupportController::class, 'show']);
  Route::get('/support', [SupportController::class, 'index']);
  Route::put('/support/{support}', [SupportController::class, 'update']);
  Route::delete('/support/{support}', [SupportController::class, 'destroy']);
  Route::post('/support', [SupportController::class, 'store']);
});

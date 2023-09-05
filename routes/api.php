<?php


use App\Http\Controllers\API\User\Auth\LoginController;
use App\Http\Controllers\API\User\Auth\LogoutController;
use App\Http\Controllers\API\User\Auth\RegisterController;
use App\Http\Controllers\API\User\Auth\ResetPasswordController;
use App\Http\Controllers\API\User\Folder\FileController;
use App\Http\Controllers\API\User\Folder\FolderController;
use App\Http\Controllers\API\User\Folder\TrashController;
use App\Http\Controllers\API\User\SupportController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Libraries\FileUploadLibrary;
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
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/forgot-password', [ResetPasswordController::class, 'sendMail']);
Route::put('reset-password/{token}', [ResetPasswordController::class, 'reset']);

Route::middleware('auth:api')->group(function () {

  Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::put('/', [UserController::class, 'update']);
    Route::get('/detail', [UserController::class, 'show']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::post('/user', [UserController::class, 'store']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    // Route::get('/user/{user}/folder/{folder}/file')
    // Route::get('/user/{user}/folder');
  });

  Route::prefix('folder')->group(function () {
    Route::get('/', [FolderController::class, 'index']);
    Route::post('/', [FolderController::class, 'store']);
    Route::get('/{folder}', [FolderController::class, 'show']);
    Route::put('/{folder}', [FolderController::class, 'update']);
    Route::delete('/{folder}', [FolderController::class, 'destroy']);
  });

  Route::prefix('file')->group(function () {
    Route::get('/', [FileController::class, 'index']);
    Route::post('/', [FileController::class, 'store']);
    Route::get('/{file}', [FileController::class, 'show']);
    Route::put('/{file}', [FileController::class, 'update']);
    Route::delete('/{file}', [FileController::class, 'destroy']);
    Route::post('/upload', [FileUploadLibrary::class, 'upload']);
  });

  Route::prefix('trash')->group(function () {
    Route::get('/', [TrashController::class, 'index']);
    Route::post('/', [TrashController::class, 'store']);
    Route::get('/{trash}', [TrashController::class, 'show']);
    Route::put('/{trash}', [TrashController::class, 'update']);
    Route::delete('/{trash}', [TrashController::class, 'destroy']);
  });

  Route::prefix('support')->group(function () {
    Route::get('/', [SupportController::class, 'index']);
    Route::post('/', [SupportController::class, 'store']);
    Route::get('/{support}', [SupportController::class, 'show']);
    Route::put('/{support}', [SupportController::class, 'update']);
    Route::delete('/{support}', [SupportController::class, 'destroy']);
  });
});

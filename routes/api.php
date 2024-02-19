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

Route::get("/download", [FolderController::class, 'download']);
Route::get("/share", [FolderController::class, 'share']);


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/forgot-password', [ResetPasswordController::class, 'sendMail']);
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword']);
Route::get('/login/google',  [LoginController::class, 'redirectToGoogle']);
Route::get('/login/google/callback',  [LoginController::class, 'handleGoogleCallback']);

Route::middleware('auth:api')->group(function () {

  Route::prefix('user')->group(function () {
    Route::put('/', [UserController::class, 'update']);
    Route::get("/", [UserController::class, 'getProfile']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::post("/change-password", [UserController::class, 'changePassword']);
  });

  Route::prefix('folder')->group(function () {
    Route::get('/', [FolderController::class, 'getRootFoldersAndFiles']);
    Route::delete("/", [FolderController::class, 'deleteFolder']);
    Route::post("/", [FolderController::class, 'createFolder']);
    Route::put("/{folder}", [FolderController::class, 'update']);
    Route::post("/upload", [FolderController::class, 'uploadFolder']);
    Route::post('/restore', [FolderController::class, 'restore']);
    Route::get('/starred', [FolderController::class, 'getFolderStarred']);
    Route::get("/{folder}", [FolderController::class, 'getFilesOfFolder']);
    // Route::get("share/{token}", [FolderController::class, 'shareByMe']);
  });

  Route::prefix('file')->group(function () {
    Route::delete("/", [FileController::class, 'deleteFile']);
    Route::put("/{file}", [FileController::class, 'update']);
    Route::post('/', [FileController::class, 'upload']);
    Route::post('/restore', [FileController::class, 'restore']);
    Route::get('/starred', [FileController::class, 'getFileStarred']);
  });

  Route::get("/search", [UserController::class, 'search']);

  Route::prefix('trash')->group(function () {
    Route::get('/', [TrashController::class, 'index']);
    Route::delete('/', [TrashController::class, 'destroy']);
  });
});

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

Route::post('/refresh-token', [LoginController::class, 'refreshToken']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/forgot-password', [ResetPasswordController::class, 'sendMail']);
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function () {

  Route::prefix('user')->group(function () {
    Route::put('/', [UserController::class, 'update']);
    Route::get("/profile", [UserController::class, 'getProfile']);
    Route::delete('/{userDel}', [UserController::class, 'destroy']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/myfile', [UserController::class, 'getRootFoldersAndFiles']);
    Route::post("/change-password", [UserController::class, 'changePassword']);

    Route::prefix('folder')->group(function () {
      Route::delete("/delete", [FolderController::class, 'deleteFolder']);
      Route::get("/{folder}", [FolderController::class, 'getFilesOfFolder']);
      Route::post("/", [FolderController::class, 'createFolder']);
      Route::put("/{folder}", [FolderController::class, 'update']);
      Route::post("/upload", [FolderController::class, 'uploadFolder']);
      Route::post("/share", [FolderController::class, 'share']);
      Route::get("share/{token}", [FolderController::class, 'shareByMe']);
    });

    Route::prefix('file')->group(function () {
      Route::delete("/delete", [FileController::class, 'deleteFile']);
      Route::put("/{file}", [FileController::class, 'update']);
      Route::post('/upload', [FileController::class, 'upload']);
    });

    Route::get("/search", [UserController::class, 'search']);
  });

  Route::prefix('trash')->group(function () {
    Route::get('/', [TrashController::class, 'index']);
    Route::post('/restore', [TrashController::class, 'restore']);
    Route::delete('/delete', [TrashController::class, 'destroy']);
  });


  // Route::group(['prefix' => 'admin', 'middleware' =>  ['checkAdmin']], function () {
  //   Route::prefix('folder')->group(function () {
  //     Route::get('/', [FolderController::class, 'index']);
  //     Route::post('/', [FolderController::class, 'store']);
  //     Route::get('/{folder}', [FolderController::class, 'show']);
  //   });
  //   Route::prefix('file')->group(function () {
  //     Route::get('/', [FileController::class, 'index']);
  //     Route::post('/', [FileController::class, 'store']);
  //     Route::get('/{file}', [FileController::class, 'show']);
  //   });

  // Route::prefix('support')->group(function () {
  //   Route::get('/', [SupportController::class, 'index']);
  //   Route::post('/', [SupportController::class, 'store']);
  //   Route::get('/{support}', [SupportController::class, 'show']);
  //   Route::put('/{support}', [SupportController::class, 'update']);
  //   Route::delete('/{support}', [SupportController::class, 'destroy']);
  // });
  // });
});

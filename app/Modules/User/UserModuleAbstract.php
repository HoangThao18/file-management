<?php

namespace App\Modules\User;

use App\Http\Libraries\HttpResponse;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Libraries\FileUploadLibrary;
use App\Models\Trash;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;

abstract class UserModuleAbstract implements UserModuleInterface
{
  protected User $user;
  protected bool $isAdmin;

  public function __construct()
  {
  }

  public function getUser()
  {
    return $this->user;
  }

  public function setUser($user)
  {
    $this->user = $user;
    return $this;
  }

  function getProfile()
  {
    return new UserResource($this->user);
  }

  function changePassword($request)
  {
    $validator = Validator::make($request->all(), [
      'password' => "required",
      'new_password' => "required|min:8",
      'confirm_password' => 'required|same:new_password',
    ]);
    if ($validator->fails()) {
      return HttpResponse::resJsonFail($validator->errors(), 400, $validator->errors()->first());
    }


    if (!(Hash::check($request->get('password'), $this->user->password))) {
      return HttpResponse::resJsonFail("Your current password does not matches with the password you provided. Please try again.");
    }

    $this->user->update(['password' => $request->new_password]);
    return HttpResponse::resJsonSuccess(null, "Password updated successfully.");
  }


  function createFolder($request)
  {
    $existingFolder = Folder::where('name', $request->name)
      ->where('user_id', $this->user->id)
      ->where('parent_folder', $request->parent_folder ?? null)
      ->first();

    if ($existingFolder) {
      return HttpResponse::resJsonFail("Folder already exists");
    }

    $newFolder = new Folder();
    $newFolder->name = $request->name;
    $newFolder->user_id = $this->user->id;
    if ($request->parent_folder) {
      $newFolder->parent_folder = $request->parent_folder;
    }
    $newFolder->save();
    return HttpResponse::resJsonCreated($newFolder);
  }

  function deleteFolder($request)
  {
    $data = $request->validated();
    foreach ($data['ids'] as  $id) {
      $folder = Folder::find($id);
      if (!$this->user->can('delete', $folder)) {
        return HttpResponse::resJsonFail("unauthorized", 403);
      }
      $folder->delete();
    }

    return  HttpResponse::resJsonSuccess("Delete successfully");
  }

  function search($search)
  {
    $folders = Folder::where('user_id', $this->user->id)->where('name', "LIKE", "%" . $search->input('search') . "%")->get();
    $files = File::where('user_id', $this->user->id)->where('name', 'LIKE', '%' . $search->input('search') . '%')->get();;
    return HttpResponse::resJsonSuccess(['folders' => FolderResource::collection($folders), 'files' => FileResource::collection($files)]);
  }

  function deleteFile($request)
  {
    $data = $request->validated();
    foreach ($data['ids'] as  $id) {
      $file = File::find($id);
      if (!$this->user->can('delete', $file)) {
        return HttpResponse::resJsonFail("unauthorized", 403);
      }
      $file->delete();
    }
    return  HttpResponse::resJsonSuccess("Delete successfully");
  }

  function uploadFile($request)
  {
    $fileUploadLibrary = new FileUploadLibrary();
    $fileRequest = $request->file('files');
    $parent = $request->parent_folder ?? null;

    if (!$fileRequest) {
      return HttpResponse::resJsonFail('No file provided.', 400);
    }

    foreach ($fileRequest as $i => $file) {
      $fileUploadLibrary->uploadFile($this->user, $file, $parent);
    }
    return  HttpResponse::resJsonSuccess("upload successfully");
  }

  function download($request)
  {
    $fileIds = $request->input('fileIds', []);
    $folderIds = $request->input('folderIds', []);

    if (empty($fileIds) && empty($folderIds)) {
      return HttpResponse::resJsonFail("please seclect file to download");
    }

    if ($request->input('parent_id')) {
      $parent = Folder::find($request->input('parent_id'));
    }

    $url = "";
    $fileName = isset($parent->name) ? $parent->name : "download.zip";

    if (empty($fileIds) && count($folderIds) === 1) {
      $folder = Folder::find($folderIds[0]);
      if (count($folder->files) == 0) {
        return HttpResponse::resJsonFail("the folder is empty");
      } else {
        $url = $this->createZip([], [$folder->id]);
        $fileName = $folder->name;
        return HttpResponse::resJsonSuccess(['url' => $url, 'fileName' => $fileName]);
      }
    }

    if (empty($folderIds) && count($fileIds) === 1) {
      $file = File::find($fileIds[0]);
      $dest = "public/" . pathinfo($file->path, PATHINFO_BASENAME);
      Storage::copy($file->path, $dest);
      $url = asset(Storage::url($dest));
      $fileName = $file->name;
      return HttpResponse::resJsonSuccess(compact("url", "fileName"));
    }

    $parentPath = isset($parent->name) ? $parent->name : "";
    $url = $this->createZip($fileIds, $folderIds, $parentPath);

    return HttpResponse::resJsonSuccess(compact('url', 'fileName'));
  }

  public function createZip($fileIds, $folderIds, $parentPath = "")
  {
    $zipPath = "zip" . Str::random() . ".zip";
    $publicPath = "public/" . $zipPath;

    if (!Storage::exists(dirname($publicPath))) {
      Storage::makeDirectory(dirname($publicPath));
    }

    $zipFile = Storage::path($publicPath);
    $zip = new ZipArchive();

    if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
      foreach ($fileIds as $id) {
        $file = File::find($id);
        $zip->addFile(Storage::path($file->path), $file->name);
      }

      foreach ($folderIds as $id) {
        $folder = Folder::with('files')->find($id);
        $this->addToZip($zip, $folder);
      }
    }
    $zip->close();
    return asset(Storage::url($zipPath));
  }

  function addToZip($zip, $folder, $parentPath = "")
  {
    $fileCount = count($folder->files);
    $subfolderCount = count($folder->subfolders);

    if ($fileCount === 0 && $subfolderCount === 0) {
      $zip->addEmptyDir($parentPath . $folder->name);
    } else {
      foreach ($folder->files as $file) {
        $zip->addFile(Storage::path($file->path), $parentPath . $folder->name . '/' . $file->name);
      }

      foreach ($folder->subfolders as $subfolder) {
        $this->addToZip($zip, $subfolder, $parentPath . $folder->name . '/');
      }
    }
  }

  public function restore($request)
  {
    $fileIds = $request->fileIds ?? [];
    $folderIds = $request->folderIds ?? [];

    $files = File::onlyTrashed()->whereIn('id', $fileIds)->get();
    $folders = Folder::onlyTrashed()->whereIn('id', $folderIds)->get();

    foreach ($files as $file) {
      $file->restore();
    }

    foreach ($folders as $folder) {
      $folder->restore();
    }
    return HttpResponse::resJsonSuccess("Restore successfully");
  }

  function softByName()
  {
  }


  function support()
  {
  }
}

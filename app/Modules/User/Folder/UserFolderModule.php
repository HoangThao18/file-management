<?php

namespace App\Modules\User\Folder;

use App\Http\Libraries\HttpResponse;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Models\File;
use App\Models\Folder;
use App\Modules\User\UserModuleAbstract;
use App\Http\Libraries\FileUploadLibrary;

class UserFolderModule extends UserModuleAbstract
{

  private Folder $folder;

  public function getFolder()
  {
    return $this->folder;
  }

  public function setFolder($folder)
  {
    $this->folder = $folder;
    return $this;
  }

  public function getRootFoldersAndFiles()
  {
    $folders =  Folder::where('user_id', $this->user->id)->wherenull('parent_folder')->paginate(10);
    $files = File::where('user_id', $this->user->id)->wherenull("folder_id")->paginate(10);
    return HttpResponse::resJsonSuccess(["folders" => FolderResource::collection($folders), "files" => FileResource::collection($files)]);
  }

  public function getFilesOfFolder()
  {
    $subFolders = $this->folder
      ->where('user_id', $this->user->id)
      ->where('parent_folder', $this->folder->id)
      ->get();

    return HttpResponse::resJsonSuccess([
      'folders' => new FolderResource($this->folder),
      'subFolders' => FolderResource::collection($subFolders)
    ]);
  }

  public function upLoadFolder($request)
  {
    $fileUploadLibrary = new FileUploadLibrary();
    $data = $request->validated();
    $filesTree = $request->files_tree;
    $parent = $request->parent_folder ?? null;
    $fileUploadLibrary->uploadFolder($filesTree, $parent, $this->user);
    return HttpResponse::resJsonSuccess(null, "Upload successfully");
  }
}

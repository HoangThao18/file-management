<?php

namespace App\Modules\User\Folder;

use App\Http\Libraries\DownloadLibrary;
use App\Http\Libraries\FileUploadLibrary;
use App\Http\Libraries\HttpResponse;
use App\Http\Requests\ActionFileRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Repositories\FolderRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FolderModule implements FolderModuleInterface
{
  private $folderRepository;
  private $user;

  function __construct(FolderRepository $folderRepository)
  {
    $this->folderRepository = $folderRepository;
  }

  public function setUser(User $user)
  {
    $this->user = $user;
  }

  public function deleteFolder($ids)
  {
    try {
      $folders = $this->folderRepository->findWhereIn('id', $ids);
      foreach ($folders as $folder) {
        if (!Auth::user()->can('delete', $folder)) {
          return HttpResponse::resJsonFail("Unauthorized to delete folder with ID: {$folder->id}");
        }
      }
      $this->folderRepository->deleteMany($ids);
      return HttpResponse::resJsonSuccess("Deleted successfully");
    } catch (Exception $e) {
      return HttpResponse::resJsonFail($e->getMessage());
    }
  }

  function createFolder($name, $parent_id = null)
  {
    $newFolder = $this->folderRepository->create(['name' => $name, 'user_id' => Auth::id(), 'parent_folder' => $parent_id]);
    return HttpResponse::resJsonCreated($newFolder);
  }


  public function getRootFoldersAndFiles()
  {
    $folders = $this->folderRepository->findWhere(['user_id' => Auth::id()])->wherenull('parent_folder');
    $files = File::where('user_id', Auth::id())->wherenull("folder_id")->get();
    return HttpResponse::resJsonSuccess(["folders" => FolderResource::collection($folders), "files" => FileResource::collection($files)]);
  }

  public function upLoadFolder($files_tree, $parent_folder)
  {
    $fileUploadLibrary = new FileUploadLibrary();
    $filesTree = $files_tree;
    $parent = $parent_folder;
    $fileUploadLibrary->uploadFolder($filesTree, $parent, Auth::id());

    return HttpResponse::resJsonSuccess(null, "Upload successfully");
  }

  public function getFilesOfFolder($folder)
  {
    $folder = $this->folderRepository->withTrashed()->find($folder);
    $subFolders = $this->folderRepository->findWhere(['parent_folder' => $folder->id]);
    return HttpResponse::resJsonSuccess([
      'files' => FileResource::collection($folder->files),
      'subFolders' => FolderResource::collection($subFolders)
    ]);
  }

  function download($fileIds, $folderIds)
  {
    $fileIds = $fileIds ?? [];
    $folderIds = $folderIds ?? [];


    if (empty($fileIds) && empty($folderIds)) {
      return HttpResponse::resJsonFail("please seclect file to download");
    }

    $url = "";
    $fileName = isset($parent) ? $parent->name : "download.zip";

    if (empty($fileIds) && count($folderIds) === 1) {
      $folder = Folder::find($folderIds[0]);
      if (count($folder->files) == 0) {
        return HttpResponse::resJsonFail("the folder is empty");
      } else {
        $fileName = $folder->name;
        $url = DownloadLibrary::createZip([], [$folder->id]);
        return HttpResponse::resJsonSuccess(['url' => $url, 'fileName' => $fileName]);
      }
    } else if (empty($folderIds) && count($fileIds) === 1) {
      $file = File::find($fileIds[0]);
      if ($file->uploaded_on_cloud) {
        $dest = "temp/" . pathinfo($file->path, PATHINFO_BASENAME);
        $content = Storage::get($file->path);
        Storage::disk("public")->put($dest, $content);

        $localPath = Storage::disk('public')->path($dest);
        return  response()->download($localPath, $file->name);
      } else {
        return  response()->download(storage_path("app/{$file->path}"), $file->name);
      }
    }

    $url = DownloadLibrary::createZip($fileIds, $folderIds);

    return HttpResponse::resJsonSuccess(compact('url', 'fileName'));
  }

  public function restore($folderIds)
  {
    if (!$folderIds) {
      return HttpResponse::resJsonFail("", 401, 'No folder provided.');
    }
    $folders = Folder::onlyTrashed()->whereIn('id', $folderIds)->get();

    foreach ($folders as $folder) {
      $folder->restore();
    }
    return HttpResponse::resJsonSuccess("Restore successfully");
  }

  function search($search)
  {
    $folders = Folder::where('user_id', $this->user->id)->where('name', "LIKE", "%" . $search->input('search') . "%")->get();
    $files = File::where('user_id', $this->user->id)->where('name', 'LIKE', '%' . $search->input('search') . '%')->get();;
    return HttpResponse::resJsonSuccess(['folders' => FolderResource::collection($folders), 'files' => FileResource::collection($files)]);
  }

  public function getFolderStarred()
  {
    $folders = $this->folderRepository->getFolderStarred();
    return HttpResponse::resJsonSuccess(FolderResource::collection($folders));
  }

  public function share($token)
  {
    $folders = $this->folderRepository->findWhereIn('token_share', $token);
    $files = file::whereIn("token_share", $token)->get();
    return HttpResponse::resJsonSuccess(['folders' => FolderResource::collection($folders), 'files' => FileResource::collection($files)]);
  }
}

<?php

namespace App\Modules\User\File;

use App\Http\Libraries\FileUploadLibrary;
use App\Http\Libraries\HttpResponse;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;

class FileModule implements FileModuleInterface
{

  private $fileRepository;

  function __construct(FileRepository $fileRepository)
  {
    $this->fileRepository = $fileRepository;
  }

  public function uploadFile($files, $parent_folder)
  {
    $fileUploadLibrary = new FileUploadLibrary();
    $parent = $parent_folder ?? null;

    if (!$files) {
      return HttpResponse::resJsonFail("", 401, 'No file provided.');
    }

    foreach ($files as  $file) {
      $fileUploadLibrary->uploadFile(Auth::id(), $file, $parent);
    }
    return  HttpResponse::resJsonSuccess([], "upload successfully");
  }

  function deleteFile($fileIds)
  {
    if (!$fileIds) {
      return HttpResponse::resJsonFail("", 401, 'No file provided.');
    }
    $files = $this->fileRepository->findWhereIn('id', $fileIds);
    foreach ($files as  $file) {
      if (!Auth::user()->can('delete', $file)) {
        return HttpResponse::resJsonFail("Unauthorized to delete folder with ID: {$file->id}");
      }
    }
    $this->fileRepository->deleteMany($fileIds);
    return  HttpResponse::resJsonSuccess("Delete successfully");
  }


  public function restore($fileIds)
  {
    if (!$fileIds) {
      return HttpResponse::resJsonFail("", 401, 'No file provided.');
    }
    $files = File::onlyTrashed()->whereIn('id', $fileIds)->get();

    foreach ($files as $file) {
      $file->restore();
    }
    return HttpResponse::resJsonSuccess("Restore successfully");
  }

  public function getFileStarred()
  {
    $files = $this->fileRepository->getFileStarred();
    return HttpResponse::resJsonSuccess(FileResource::collection($files));
  }
}

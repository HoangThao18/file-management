<?php

namespace App\Http\Libraries;

use App\Jobs\uploadFileToCloud;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadLibrary
{

  public  function upLoadFile($userId, $file, $parent)
  {

    $fileName = $file->getClientOriginalName();
    $filePath = 'public/uploads/users/' . $userId;
    $path = $file->store($filePath, "local");

    $fileNew = new File([
      'name' => $fileName,
      'size' => $file->getSize(),
      'path' => $path,
      'user_id' => $userId,
      'folder_id' => $parent,
      'uploaded_on_cloud' => 0
    ]);
    $fileNew->save();

    // background job
    uploadFileToCloud::dispatch($fileNew);
  }

  public function uploadFolder($fileTree, $parent, $userId)
  {
    foreach ($fileTree as $name => $value) {
      if (is_array($value)) {
        $newFolder = new Folder();
        $newFolder->name = $name;
        $newFolder->user_id = $userId;
        $newFolder->parent_folder = $parent;
        $newFolder->save();
        $this->upLoadFolder($value, $newFolder->id, $userId);
      } else {
        $this->upLoadFile($userId, $value, $parent);
      }
    }
  }
}

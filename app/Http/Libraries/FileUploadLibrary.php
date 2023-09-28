<?php

namespace App\Http\Libraries;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadLibrary
{

  public  function upLoadFile($user, $file, $parent)
  {

    $fileName = $file->getClientOriginalName();
    $filePath = 'public/uploads/users/' . $user->id;
    $path = $file->storeAs($filePath, $fileName);

    $fileNew = new File([
      'name' => $fileName,
      'size' => $file->getSize(),
      'path' => $path,
      'user_id' => $user->id,
      'folder_id' => $parent
    ]);
    $fileNew->save();
  }

  public  function uploadFolder($fileTree, $parent, $user)
  {
    foreach ($fileTree as $name => $value) {
      if (is_array($value)) {
        $newFolder = new Folder();
        $newFolder->name = $name;
        $newFolder->user_id = $user->id;
        $newFolder->parent_folder = $parent;
        $newFolder->save();
        $this->upLoadFolder($value, $newFolder->id, $user);
      } else {
        $this->upLoadFile($user, $value, $parent);
      }
    }
  }
}

<?php

namespace App\Http\Libraries;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DownloadLibrary
{


  public static function createZip($fileIds, $folderIds)
  {
    $zipPath =   "zip/" . Str::random() . ".zip";
    $publicPath =  $zipPath;

    if (!Storage::exists(dirname($publicPath))) {
      Storage::disk('public')->makeDirectory(dirname($publicPath));
    }

    $zipFile = Storage::disk("public")->path($publicPath);
    $zip = new ZipArchive();

    if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
      foreach ($fileIds as $id) {
        $file = File::find($id);
        $localPath = Storage::disk('local')->path($file->path);

        if ($file->uploaded_on_cloud == 1) {

          $dest =  "temp/" . pathinfo($file->path, PATHINFO_BASENAME);

          $content = Storage::get($file->path);
          Storage::disk("public")->put($dest, $content);
          $localPath = Storage::disk('public')->path($dest);
        }
        $zip->addFile($localPath, $file->name);
      }

      foreach ($folderIds as $id) {
        $folder = Folder::with('files')->find($id);
        self::addToZip($zip, $folder, "");
      }
    }
    $zip->close();
    return asset(Storage::disk('local')->url($zipPath));
  }

  public static function addToZip($zip, $folder, $zipPath = "")
  {
    $fileCount = count($folder->files);
    $subfolderCount = count($folder->subfolders);

    if ($fileCount === 0 && $subfolderCount === 0) {
      $zip->addEmptyDir($zipPath . $folder->name);
    } else {

      foreach ($folder->files as $file) {
        $localPath = Storage::disk("local")->path($file->path);

        if ($file->uploaded_on_cloud == 1) {
          $dest = "temp/" . pathinfo($file->path, PATHINFO_BASENAME);
          $content = Storage::get($file->path);
          Storage::disk("public")->put($dest, $content);
          $localPath = Storage::disk("public")->path($dest);
        }

        $zip->addFile($localPath, $zipPath . $folder->name . '/' . $file->name);
      }

      foreach ($folder->subfolders as $subfolder) {
        self::addToZip($zip, $subfolder, $zipPath . $folder->name . '/');
      }
    }
  }
}

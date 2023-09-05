<?php

namespace App\Http\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadLibrary
{

  public function upLoad(Request $request)
  {
    $file = $request->file('file');

    if (!$file) {
      return HttpResponse::resJsonFail('No file provided.', 400);
    }
    $fileName = $file->getClientOriginalName();
    $filePath = 'uploads/users' . $request->user()->id;

    if (Storage::exists($filePath . '/' . $fileName)) {
      return HttpResponse::resJsonFail('File already exists', 400);
    }

    $path = $file->storeAs($filePath, $fileName);
    return $path;
  }
}

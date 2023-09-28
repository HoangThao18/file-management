<?php

namespace App\Modules\User;

use App\Models\Folder;
use Illuminate\Http\Client\Request;

interface UserModuleInterface
{
  function getProfile();

  function changePassword($request);

  function uploadFile($request);

  function deleteFile($file);

  function search($search);

  function createFolder($request);

  function deleteFolder($folder);

  function download($request);

  function softByName();


  function support();
}

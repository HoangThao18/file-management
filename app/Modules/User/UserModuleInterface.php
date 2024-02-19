<?php

namespace App\Modules\User;

use App\Models\Folder;
use Illuminate\Http\Client\Request;

interface UserModuleInterface
{
  function getProfile();

  function setUser($user);

  function changePassword($request);
}

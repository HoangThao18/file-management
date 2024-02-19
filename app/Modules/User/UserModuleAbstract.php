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
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;

abstract class UserModuleAbstract implements UserModuleInterface
{
  protected User $user;
  protected User $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
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

  function changePassword($newsPassword)
  {

    if (!(Hash::check($newsPassword, $this->user->password))) {
      return HttpResponse::resJsonFail("Your current password does not matches with the password you provided. Please try again.");
    }

    $this->userRepository->update(['password' => $newsPassword]);
    return HttpResponse::resJsonSuccess(null, "Password updated successfully.");
  }
}

<?php

namespace App\Modules\User;

use App\Http\Libraries\HttpResponse;

class UserAdmin extends UserModuleAbstract
{

  public function deleteUser($userDel)
  {
    if ($this->user->isAdmin()) {
      $userDel->delete();
      return HttpResponse::resJsonSuccess(null);
    }
    return HttpResponse::resJsonFail("unauthorized", 403);
  }
}

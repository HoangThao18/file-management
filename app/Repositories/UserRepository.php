<?php

namespace App\Repositories;

use App\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{
  public function model()
  {
    return User::class;
  }

  public function findByEmail($email)
  {
    return $this->model->where('email', $email)->first();
  }
}

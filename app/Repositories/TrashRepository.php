<?php

namespace App\Repositories;

use App\Models\Trash;
use Prettus\Repository\Eloquent\BaseRepository;

class TrashRepository extends BaseRepository
{
  public function model()
  {
    return Trash::class;
  }
}

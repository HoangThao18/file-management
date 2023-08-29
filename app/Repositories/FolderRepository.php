<?php

namespace App\Repositories;

use App\Models\Folder;
use Prettus\Repository\Eloquent\BaseRepository;

class FolderRepository extends BaseRepository
{
  public function model()
  {
    return Folder::class;
  }
}

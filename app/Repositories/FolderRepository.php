<?php

namespace App\Repositories;

use App\Models\Folder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Http\Resources\FolderResource;

class FolderRepository extends BaseRepository
{
  public function model()
  {
    return Folder::class;
  }
}

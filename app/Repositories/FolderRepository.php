<?php

namespace App\Repositories;

use App\Models\Folder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Http\Resources\FolderResource;
use Illuminate\Support\Facades\Auth;

class FolderRepository extends BaseRepository
{
  public function model()
  {
    return Folder::class;
  }

  public function deleteMany($ids)
  {
    $this->model->whereIn('id', $ids)->delete();
  }

  public function getFolderStarred()
  {
    return $this->model->where('user_id', Auth::id())->where('is_starred', 1)->get();
  }
}

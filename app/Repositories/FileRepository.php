<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;

class FileRepository extends BaseRepository
{
  public function model()
  {
    return File::class;
  }

  public function deleteMany($ids)
  {
    $this->model->whereIn('id', $ids)->delete();
  }

  public function getFileStarred()
  {
    return $this->model->where('user_id', Auth::id())->where('is_starred', 1)->get();
  }
}

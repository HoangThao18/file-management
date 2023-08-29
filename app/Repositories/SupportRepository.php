<?php

namespace App\Repositories;

use App\Models\Support;
use Prettus\Repository\Eloquent\BaseRepository;

class SupportRepository extends BaseRepository
{
  public function model()
  {
    return Support::class;
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'size', 'path', 'description', 'link_share', 'folder_id', 'user_id'];
    protected $table = "files";

    public function getFileSize()
    {
        $units = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
        $pow = $this->size > 0 ? floor(log($this->size) / log(1024)) : 0;
        return number_format($this->size / pow(1024, $pow), 2, ".", ",") . " " . $units[$pow];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user()->name ?? 'anonymous';
        });
    }
}

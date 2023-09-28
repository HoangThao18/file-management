<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'size', 'parent_folder', 'path', 'link_share', 'user_id'];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_folder', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user()->name ?? 'anonymous';
        });
    }
}

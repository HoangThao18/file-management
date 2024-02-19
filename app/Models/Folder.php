<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'size', 'parent_folder', 'path', 'link_share', 'user_id', "is_starred"];

    public function files()
    {
        return $this->hasMany(File::class, 'folder_id', 'id')->whereNull('deleted_at');
    }

    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_folder', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->token_share = Str::random(10);
            $model->created_by = Auth::id();
        });
    }
}

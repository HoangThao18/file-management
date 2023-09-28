<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function delete(User $user, File $file)
    {
        return $user->isAdmin() || $user->id === $file->user_id;
    }

    public function update(User $user, File $file)
    {
        return $user->isAdmin() || $user->id === $file->user_id;
    }
}

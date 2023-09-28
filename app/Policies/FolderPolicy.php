<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;

class FolderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function delete(User $user, Folder $folder)
    {
        return $user->isAdmin() || $user->id === $folder->user_id;
    }

    public function update(User $user, Folder $folder)
    {
        return $user->isAdmin() || $user->id === $folder->user_id;
    }
}

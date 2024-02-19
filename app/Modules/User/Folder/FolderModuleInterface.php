<?php

namespace App\Modules\User\Folder;

use App\Models\User;

interface FolderModuleInterface
{
  function setUser(User $user);

  function deleteFolder($ids);

  function createFolder($name, $parent_id);

  function getRootFoldersAndFiles();

  function getFilesOfFolder($folder);

  function upLoadFolder($files_tree, $parent);

  function download($fileIds, $folderIds);

  function restore($folderIds);

  function search($key);

  function getFolderStarred();

  function share($token);
}

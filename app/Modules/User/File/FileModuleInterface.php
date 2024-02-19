<?php

namespace App\Modules\User\File;

interface FileModuleInterface
{
  function uploadFile($file, $parent_folderId);

  function deleteFile($ids);

  function restore($ids);

  function getFileStarred();
}

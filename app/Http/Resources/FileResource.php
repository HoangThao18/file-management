<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $extension = pathinfo($this->name, PATHINFO_EXTENSION);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'size' => $this->getFileSize(),
            "mime" => $extension,
            'folder_id' => $this->folder_id,
            'is_starred' => $this->is_starred,
            'token_share' => $this->token_share,
            'path' => $this->path,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by
        ];
    }
}

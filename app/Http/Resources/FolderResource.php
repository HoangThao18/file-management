<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FileResource;

class FolderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'size' => $this->size,
            'parent_folder' => $this->parent_folder,
            'token_share' => $this->token_share,
            'is_starred' => $this->is_starred,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by
        ];
    }
}

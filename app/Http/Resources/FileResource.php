<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'folder_id' => $this->folder_id,
            'path' => $this->path,
            'status' => $this->status,
            'link_share' => $this->link_share,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'pakage_type' => $this->pakage_type,
            'package_register_date' => $this->package_register_date,
            'package_expiration_date' => $this->package_expiration_date,
            'max_storage' => $this->max_storage,
        ];
    }
}

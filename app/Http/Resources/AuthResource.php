<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'role_type' => $this->role_type,
            'role_id' => $this->role_id, // Make sure you include all the fields you want to return
        ];
    }
}

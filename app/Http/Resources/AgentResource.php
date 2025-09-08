<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
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
            'agent_id' => $this->id,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'middle_name' => $this->user->middle_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'phone_number' => $this->user->phone_number,
            ] : null,
            'police_clearance' => $this->police_clearance_path,
            'cv' => $this->cv_path,
            'photo' => $this->passport_photo_path,
            'diploma_certificate' => $this->diploma_certificate_path,
            'degree_certificate' => $this->degree_certificate_path,
        ];
    }
}

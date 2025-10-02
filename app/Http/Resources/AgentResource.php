<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ExperienceLevel;
use App\Models\EducationLevel;

class AgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // experience_id and education_level_id name
        $experienceLevel = ExperienceLevel::find($this->experience_level_id);
        $educationLevel = EducationLevel::find($this->education_level_id);
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
            'ira_certificate' => $this->ira_certificate,
            'id_number' => $this->id_number,
            'id_document' => $this->id_path,
            'education_level' => $educationLevel ? $educationLevel->name : null,
            'experience_level' => $experienceLevel ? $experienceLevel->name : null,
        ];
    }
}

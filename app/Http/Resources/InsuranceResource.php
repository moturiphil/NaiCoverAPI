<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        // return [
        //     'insurance_id' => $this->id,
        //     'name' => $this->name,
        //     'contact_person' => $this->contact_person,
        //     'contact_email' => $this->contact_email,
        //     'contact_phone' => $this->contact_phone,
        // ];
        return parent::toArray($request);
    }
}

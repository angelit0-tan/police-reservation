<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'pin' => $this->pin,
            'is_confirmed' => !empty($this->confirmed_at) ? 'Yes' : 'No',
            'valid_from' => $this->pin_valid_from,
            'valid_until' => $this->pin_valid_until,
            'created_at' => $this->created_at
        ];
    }
}

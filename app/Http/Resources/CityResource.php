<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'postalCode' => $this->postal_code,
            'provincy' => $this->whenLoaded('provincy', fn () => $this->getProvincyRelation())
        ];
    }

    /**
     * get the provincy relation and set into an array
     *
     * @return array
     */
    private function getProvincyRelation(): array
    {
        return ['data' => new ProvincyResource($this->provincy)];
    }
}

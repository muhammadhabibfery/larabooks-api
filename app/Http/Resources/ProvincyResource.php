<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProvincyResource extends JsonResource
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
            'cities' => $this->whenLoaded('cities', fn () => $this->getCitiesRelation())
        ];
    }

    /**
     * get the cities relation and set into an array
     *
     * @return array
     */
    private function getCitiesRelation(): array
    {
        return ['data' => CityResource::collection($this->cities)];
    }
}

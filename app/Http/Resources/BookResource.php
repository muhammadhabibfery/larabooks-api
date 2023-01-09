<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'cover' => $this->cover,
            'price' => $this->price,
            'views' => $this->views,
            'weight' => $this->weight,
            'stock' => $this->stock,
            'city' => $this->whenLoaded('city', fn () => $this->getCityRelation()),
            'categories' => $this->whenLoaded('categories', fn () => $this->getCategoriesRelation()),
            'quantity' => $this->whenPivotLoaded('book_order', fn () => $this->pivot->quantity)
        ];
    }

    /**
     * get the categories relation and set into an array
     *
     * @return array
     */
    private function getCategoriesRelation(): array
    {
        return ['data' => CategoryResource::collection($this->categories)];
    }

    /**
     * get the city relation and set into an array
     *
     * @return array
     */
    private function getCityRelation(): array
    {
        return ['data' => new CityResource($this->city)];
    }
}

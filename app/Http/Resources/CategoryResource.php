<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'image' => $this->image,
            'books' => $this->whenLoaded('books', fn () => $this->getBooksRelation())
        ];
    }

    /**
     * get the books relation and set into an array
     *
     * @return array
     */
    private function getBooksRelation(): array
    {
        $books = BookResource::collection($this->books()->paginate())->response()->getData(true);
        $result = ['data' => $books['data']];

        if (count($books['data']) > 0)
            $result = array_merge($result, ['pages' => ['links' => $books['links'], 'meta' => $books['meta']]]);

        return $result;
    }
}

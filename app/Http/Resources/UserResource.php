<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $this->avatar,
            'orders' => $this->whenLoaded('orders', fn () => $this->getOrdersRelation())
        ];
    }

    /**
     * get the orders relation and set into an array
     *
     * @return array
     */
    private function getOrdersRelation(): array
    {
        return ['data' => OrderResource::collection($this->orders)];
    }
}

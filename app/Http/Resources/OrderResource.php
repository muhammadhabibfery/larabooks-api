<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'invoiceNumber' => $this->invoice_number,
            'totalPrice' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'books' => $this->whenLoaded('books', fn () => ['data' => BookResource::collection($this->books)]),
        ];
    }
}

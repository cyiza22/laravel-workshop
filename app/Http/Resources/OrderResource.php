<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'status' => $this->resource->status,
            'user_id' => $this->resource->user_id,

            'items_count' => $this->whenCounted('orderItems'),

            'items' => OrderItemResource::collection(
                $this->whenLoaded('orderItems')
            ),

        
        ];
    }
}

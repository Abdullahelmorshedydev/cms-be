<?php

namespace Transformers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->display_name,
            'code' => $this->code,
            'new_or_used' => $this->new_or_used,
            'price' => $this->price,
            'vehicle_model_id' => $this->vehicle_model_id,
            'hidden' => $this->hidden,
            'variant_id' => $this->variant_id,
            'image' => $this->image(),
        ];
    }
}

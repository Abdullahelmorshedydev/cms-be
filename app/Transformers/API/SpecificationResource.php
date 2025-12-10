<?php

namespace Transformers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $spec = $this->spec;
        $group = $spec->group;
        return [
            "id" => $this->specs_value_id,
            "value" => $this->value,
            "name" => $spec->name,
            "group_name" => $group->name,
            "group_image" => getFullUrl($group->image?->image_path, $group->image?->name) ?? null,
        ];
    }
}

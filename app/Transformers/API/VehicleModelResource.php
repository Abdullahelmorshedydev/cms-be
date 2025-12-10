<?php

namespace Transformers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Vehicle\Transformers\ExteriorColorableResource;
use Modules\Vehicle\Transformers\InteriorColorableResource;
use Modules\Vehicle\Transformers\VehicleMakeResource;

class VehicleModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "exterior_colors" => ExteriorColorableResource::collection($this->exteriorColorables),
            "interior_colors" => InteriorColorableResource::collection($this->interiorColorables),
            "body_types" => $this->bodyTypes,
            "vehicle_make" => new VehicleMakeResource($this->vehicleMake),
            "description" => $this->description,
            "code" => $this->code,
            "slug" => $this->slug,
            "hidden" => $this->hidden,
            "featured" => $this->featured,
            "meta_title" => $this->meta_title,
            "meta_description" => $this->meta_description,
            "long_description" => $this->long_description,
            "order" => $this->order,
            "treated_as" => $this->treated_as,
            "hex_code" => $this->hex_code,
            'starting_from' => $this->starting_from,
            'media' => $this->getMediaResponse(),
        ];
    }
}

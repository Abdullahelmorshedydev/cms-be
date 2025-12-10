<?php

namespace Transformers\API;

use App\HelperClasses\CmsHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleVariantSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $variantService = app(VariantService::class);
        return [
            "id" => $this->when($this->id != null, $this->id, null),
            "price" => $this->price,
            "name" => $this->name,
            "body_type" => $this->bodyType ? $this->bodyType->description : null,
            "fuel_type" => $this->fuelType ? [
                "code" => $this->fuelType->code,
                "name" => $this->fuelType->getTranslations('name') ?? [],
                "description" => $this->fuelType->description
            ] : [],
            "model_year" => $this->model_year,
            "display_name" => $this->translations['display_name'],
            "warranty_months" => $this->warranty_months,
            "image" => $this->when($this->display_name_image, CmsHelpers::getFullUrl($this->colorImagePath(), $this->display_name_image), null),
            "application_no" => $this->when($this->application_no != null, $this->application_no),
            "application_created_at" => $this->when($this->application_created_at != null, $this->application_created_at),
            'status' => $this->when($this->status != null, $this->status),
            'exterior_colors' => $this->when(
                !$this->categorizedByBodyType,
                fn() => $variantService->handleVariantExteriorColorsWithTrimsAndImages($this, $this->used),
                []
            ),
            "images" => $this->whenLoaded('exteriorImages'),
            "addons" => $this->when(
                !$this->categorizedByBodyType,
                fn() => AccessoryResource::collection($this->shownAccessories),
                []
            ),
            "specifications" => $this->when(
                !$this->categorizedByBodyType,
                fn() => $this->specValues()->with('spec')->get(),
                []
            ),
            "featured_specifications" => SpecificationResource::collection($this->featuredSpecValues()->with('spec')->get()),
        ];
    }
}

<?php

namespace App\Transformers\Admin;

use App\HelperClasses\CmsHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $last_audit = $this->sections->map(function ($section) {
            return $section->last_audit;
        })->sortByDesc('created_at')->first();
        $last_audit = $this->last_audit?->first()?->created_at > $last_audit?->first()?->created_at ? $this->last_audit : $last_audit;
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? CmsHelpers::getPageNameFromModel($this->resource->first()->parent_type),
            'sections' => SectionResource::collection($this->sections ?? $this->resource),
            'last_updator' => $last_audit?->first()?->auditor,
            'last_updated_at' => $last_audit?->first()?->created_at,
            // 'last_updated_changes' => $last_audit?->first()?->changes,
        ];
    }
}

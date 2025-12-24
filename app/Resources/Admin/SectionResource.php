<?php

namespace App\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
            'type' => $this->type,
            'content' => $this->content,
            'disabled' => $this->disabled,
            'button_data' => $this->button_data,
            'button_text' => $this->button_text,
            'button_text_translations' => $this->getTranslations('button_text'),
            'button_type' => $this->button_type,
            'media' => $this->getMediaResponse(),
            'models' => $this->getModels(),
            'sub_sections' => SectionResource::collection($this->sections),
        ];
    }
}

<?php

namespace App\Transformers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionTypeResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
      'description' => $this->description,
      'fields' => $this->fields,
      'image' => $this->getFirstMediaUrl('image'),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}

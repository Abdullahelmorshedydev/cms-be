<?php

namespace App\Services;

use App\Models\SectionType;
use Illuminate\Support\Facades\DB;
use App\Services\MediaService;

class SectionTypeService
{
  public function __construct(
    protected MediaService $mediaService
  ) {
  }

  public function getAll()
  {
    return SectionType::all();
  }

  public function create(array $data): SectionType
  {
    DB::beginTransaction();
    try {
      $sectionType = SectionType::create($data);

      if (isset($data['image'])) {
        $this->mediaService->uploadImage($data['image'], $sectionType, $sectionType->name ?? null, 'image');
      }

      DB::commit();
      return $sectionType;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  public function update(SectionType $sectionType, array $data): SectionType
  {
    DB::beginTransaction();
    try {
      $sectionType->update($data);

      if (isset($data['image'])) {
        // Remove existing image if any
        $this->mediaService->removeImage($sectionType);
        // Upload new image
        $this->mediaService->uploadImage($data['image'], $sectionType, $sectionType->name ?? null);
      }

      DB::commit();
      return $sectionType;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  public function delete(SectionType $sectionType): void
  {
    // Remove associated media through MediaService
    $this->mediaService->removeImage($sectionType);
    $sectionType->delete();
  }

  public function getById($id): ?SectionType
  {
    return SectionType::find($id);
  }
}

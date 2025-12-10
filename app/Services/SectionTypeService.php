<?php

namespace App\Services;

use App\Models\SectionType;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SectionTypeService
{
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
        $sectionType->addMedia($data['image'])->toMediaCollection('image');
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
        $sectionType->addMedia($data['image'])->toMediaCollection('image');
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
    $sectionType->delete();
  }

  public function getById($id): ?SectionType
  {
    return SectionType::find($id);
  }
}

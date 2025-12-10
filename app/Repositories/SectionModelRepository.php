<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use App\Models\SectionModel;

class SectionModelRepository extends BaseRepository
{
    protected function model(): string
    {
        return SectionModel::class;
    }
    public function deleteRelation($section_id, $deleted_models)
    {
        foreach ($deleted_models as $model) {
            $this->model->where('section_id', $section_id)->where('model_type', $model['model_type'])->where('model_id', $model['model_id'])->delete();
        }
    }
    public function deleteRemovedDataModel(array $data)
    {
        if (!empty($data))
            $this->model->whereIn('id', $data)->delete();
    }
}

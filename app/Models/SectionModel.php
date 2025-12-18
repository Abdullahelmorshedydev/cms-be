<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class SectionModel extends Model
{
    use HasFactory, HasTranslations;

    protected $translatable = [];

    protected $fillable = ["section_id", "model_id", "model_type", "order"];

    function modelable()
    {
        return $this->morphTo('model_type');
    }

    public function model()
    {
        return $this->morphTo();
    }
}

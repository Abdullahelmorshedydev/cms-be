<?php

namespace App\Models;

use App\Traits\DynamicMediaCollectionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\MultiTenantable;
// use  Database\Factories\SectionModelFactory;

class SectionModel extends Model
{
    use HasFactory, DynamicMediaCollectionTrait;

    /**
     * The attributes that are mass assignable.
     */
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

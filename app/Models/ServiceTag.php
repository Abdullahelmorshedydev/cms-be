<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiceTag extends Pivot
{
    protected $table = 'service_tags';

    public $incrementing = true;

    public $timestamps = true;
}

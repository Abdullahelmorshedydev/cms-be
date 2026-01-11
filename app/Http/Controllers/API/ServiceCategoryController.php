<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ServiceCategoryService;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function __construct(
        protected ServiceCategoryService $service
    ) {
    }

    public function __invoke(Request $request)
    {
        return formatResponse(
            $this,
            $this->service->index(
                $request->all(),
                [
                    'services.image',
                    'services.tags'
                ]
            )
        );
    }
}

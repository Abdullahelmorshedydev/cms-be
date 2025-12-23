<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        protected ServiceService $service
    ) {
    }

    public function __invoke(Request $request)
    {
        return formatResponse(
            $this,
            $this->service->index(
                $request->all(),
                [
                    'image',
                    'tags'
                ]
            )
        );
    }
}

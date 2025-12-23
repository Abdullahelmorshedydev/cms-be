<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $service
    ) {
    }

    public function __invoke(Request $request)
    {
        return formatResponse(
            $this,
            $this->service->index(
                $request->all(),
                [
                    'image'
                ]
            )
        );
    }
}

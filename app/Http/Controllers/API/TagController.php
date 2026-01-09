<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(
        protected TagService $service
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

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PartnerService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct(
        protected PartnerService $service
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

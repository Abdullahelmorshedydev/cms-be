<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormsRequest;
use App\Services\FormService;
use App\Services\MediaService;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct(
        protected FormService $service,
        protected MediaService $mediaService
    ) {
    }

    public function __invoke(FormsRequest $request): mixed
    {
        return formatResponse(
            $this,
            $this->service->store($request->all(), function ($model, $data) {
                if (isset($data['file']) && is_file($data['file']))
                    $this->mediaService->uploadFile($data['file'], $model, 'file');
            })
        );
    }
}

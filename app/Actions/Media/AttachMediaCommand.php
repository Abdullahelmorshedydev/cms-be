<?php

namespace App\Actions\Media;


use App\Enums\MediaMimeTypeEnum;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Vehicle\Http\Requests\CreateVehicleMakeRequest;

class AttachMediaCommand
{
    public function __construct(
        protected HasMedia $model,
        protected array $mediaMap,
        protected MediaMimeTypeEnum|null $mimeType = null,
        protected int $sizeLimit
    ) {
    }

    public function execute(): void
    {
        $inputs = $this->flattenRequestInputs(request()->all());
        foreach ($inputs as $key => $input) {
            $key = (string) $key;
            if (!array_key_exists($key, $this->mediaMap)) {
                continue;
            }
            $collection = $this->getCollection($key);

            $inputItems = is_array($input) ? $input : [$input];

            [$files, $urlsToKeep] = $this->getFiles($inputItems);

            if ($urlsToKeep) {
                $this->removeOldFilesFromModel($collection, $urlsToKeep);
            } else {
                $this->model->clearMediaCollection($collection);
            }
            foreach ($files as $index => $file) {
                $this->attachFile($file['file'], $key, $file['order'] ?? null, $collection);
            }
        }
    }

    protected function attachFile($file, string $key, int|null $order, $collection): void
    {
        $validator = \Validator::make(
            [$key => $file],
            [
                $key =>
                    'required|mimes:' . MediaMimeTypeEnum::getMediaValidationRules($this->mimeType) . '|max:' . $this->sizeLimit
            ]
        );

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $media = $this->model
            ->addMedia($file)
            ->usingFileName($file->getClientOriginalName());
        if ($order) {
            $media->withCustomProperties(['image_order' => $order]);
        }
        $media->toMediaCollection($collection);
    }
    protected function getCollection(string $key): string
    {
        return $this->mediaMap[$key] ?? $key;
    }
    protected function getFiles(array $inputItems): array
    {
        $files = [];
        $urlsToKeep = [];
        foreach ($inputItems as $item) {
            if ($item instanceof \Illuminate\Http\UploadedFile) {
                $files[] = [
                    'file' => $item,
                ];
            } elseif (is_string($item)) {
                $urlsToKeep[] = ['file' => $item];
            } elseif (is_array($item)) {
                if (is_string($item['file'])) {
                    $urlsToKeep[] = [
                        'file' => $item['file'],
                        'order' => $item['order']
                    ];
                } else {
                    $files[] = [
                        'file' => $item['file'],
                        'order' => $item['order']
                    ];

                }
            }
        }
        return [$files, $urlsToKeep];
    }
    protected function removeOldFilesFromModel(string $collection, array $urlsToKeep)
    {
        $urlsToKeepMap = array_column($urlsToKeep, null, 'file');
        $this->model->getMedia($collection)->each(function ($media) use ($urlsToKeepMap) {
            $image_url = $media->getUrl();
            if (!isset($urlsToKeepMap[$image_url])) {
                $media->delete();
            } elseif (isset($urlsToKeepMap[$image_url]['order'])) {
                $media->setCustomProperty('image_order', $urlsToKeepMap[$image_url]['order'])->save();
            }
        });
    }

    protected function flattenRequestInputs(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '[' . $key . ']';

            if (is_array($value) && $this->isAssoc($value)) {
                $result += $this->flattenRequestInputs($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    protected function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

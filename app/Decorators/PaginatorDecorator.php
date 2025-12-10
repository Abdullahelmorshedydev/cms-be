<?php
namespace App\Decorators;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;


class PaginatorDecorator
{
    public static function apply($data)
    {
        $resource = $data instanceof JsonResource ? $data->resource : $data;

        if ($resource instanceof LengthAwarePaginator || $resource instanceof PaginatorContract) {
            return [
                'data' => $data, // Return the original resource or paginator
                'meta' => [
                    'current_page' => $resource->currentPage(),
                    'from' => $resource->firstItem(),
                    'last_page' => $resource->lastPage(),
                    'path' => $resource->path(),
                    'per_page' => $resource->perPage(),
                    'to' => $resource->lastItem(),
                    'total' => $resource->total(),
                    'first_page_url' => $resource->url(1),
                    'last_page_url' => $resource->url($resource->lastPage()),
                    'next_page_url' => $resource->nextPageUrl(),
                    'prev_page_url' => $resource->previousPageUrl(),
                    'links' => method_exists($resource, 'toArray') ? ($resource->toArray()['links'] ?? []) : [],
                ],
            ];
        }

        return [
            'data' => $data,
            'meta' => null,
        ];
    }
}

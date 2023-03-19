<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

abstract class ResourceJsonBase extends JsonResource
{
    protected array $dateTimeFields = [
        'createTime',
        'updateTime',
    ];

    public function __construct($resource)
    {
        $target = is_array($resource) ? new StdObject($resource) : $resource;
        parent::__construct($target);
    }

    public function toArray($request): array
    {
        if (is_null($this->resource)) {
            return [];
        }

        return $this->convert($this->getPayload($request));
    }

    public function getPagination(LengthAwarePaginator $resource): array
    {
        return [
            'meta' => [
                'totalRows'   => $resource->total(),
                'totalPages'  => $resource->lastPage(),
                'pageSize'    => $resource->perPage(),
                'currentPage' => $resource->currentPage(),
            ],
        ];
    }

    protected function convert(array $payload): array
    {
        foreach ($this->dateTimeFields as $field) {
            if (Arr::has($payload, $field)) {
                $value = Arr::get($payload, $field);

                if ($value instanceof Carbon) {
                    Arr::set($payload, $field, $value->toIso8601String());
                }
            }
        }

        return $payload;
    }

    abstract protected function getPayload(Request $request): array;
}

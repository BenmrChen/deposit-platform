<?php

namespace Modules\Crypto\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a new instance of the model.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = []): Model
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * 以 id 取得obj
     *
     * @param mixed $id object id
     * @param boolean $isLock is lock for update
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findById($id, $isLock = false): ?Model
    {
        $query = $this->model->where('id', '=', $id);

        if ($isLock) {
            return $query->lockForUpdate()->first();
        }

        return $query->first();
    }

    public function getSorting(string $sort): array
    {
        $order = 'asc';

        if (strpos($sort, '-') === 0) {
            $order = 'desc';
            $sort  = substr($sort, 1);
        }

        $column = str_replace('-time', 'd_at', $sort);
        $column = str_replace('-', '_', $column);

        return [
            'column' => $column,
            'order'  => $order,
        ];
    }
}

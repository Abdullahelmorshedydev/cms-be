<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract protected function model(): string;

    public function getAll(array $relations = [], ?bool $paginate = true, ?string $orderBy = 'id', ?string $direction = 'desc', array $withCount = [])
    {
        $builder = $this->model;

        if ($this->hasFilter()) {
            $builder = $builder::filter();
        }
        if (!empty($relations)) {
            $builder = $builder->with($relations);
        }

        if (!empty($withCount)) {
            $builder = $builder->withCount($withCount);
        }

        $builder = $builder->orderBy($orderBy, $direction);

        if (!$paginate) {
            return $builder->get();
        }

        $perPage = request('per_page', 15);
        return $builder->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findOne($id, array $relations = [])
    {
        return $this->model->with($relations)->find($id);
    }
    public function findOneOrFail($id, array $relations = [])
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function update($model, array $data): bool
    {
        return $model->update($data);
    }

    public function findByWith(array $criteria, array $columns = ['*'], array $relations = [''])
    {
        $builder = $this->model->query();

        foreach ($criteria as $key => $value) {
            $operator = '=';

            if (is_array($value)) {
                $operator = $value['operator'] ?? $operator;
                $value = $value['value'];
            }
            $builder->where($key, $operator, $value);
            if (strpos($key, '.') !== false) {
                list($relation, $relationKey) = explode('.', $key);

                $builder->whereHas($relation, function ($query) use ($relationKey, $operator, $value) {
                    $query->where($relationKey, $operator, $value);
                });
            } else {
                $builder->where($key, $operator, $value);
            }

        }
        return $builder->with($relations)->get($columns);
    }

    public function findBy(array $criteria, array $columns = ['*'], array $relations = [], ?bool $paginate = true, ?string $orderBy = 'id', ?string $direction = 'desc')
    {
        $builder = $this->model->query();
        foreach ($criteria as $key => $value) {
            $operator = '=';
            if (is_array($value)) {
                $operator = $value['operator'] ?? $operator;
                $value = $value['value'];
            }
            $builder->where($key, $operator, $value);
            if (strpos($key, '.') !== false) {
                list($relation, $relationKey) = explode('.', $key);
                $builder->whereHas($relation, function ($query) use ($relationKey, $operator, $value) {
                    $query->where($relationKey, $operator, $value);
                });
            } else {
                $builder->where($key, $operator, $value);
            }
        }

        $builder->with($relations);
        $builder->orderBy($orderBy, $direction);
        $builder->select($columns);

        if (!$paginate) {
            return $builder->get();
        }

        $perPage = request('per_page', 15);
        return $builder->paginate($perPage);
    }

    public function findOneBy(array $criteria = [], array $columns = ['*'], array $relations = [])
    {
        $builder = $this->model->query();

        foreach ($criteria as $key => $value) {
            $operator = '=';
            if (is_array($value)) {
                $operator = $value['operator'] ?? $operator;
                $value = $value['value'];
            }
            $builder->where($key, $operator, $value);
        }

        return $builder->with($relations)->first($columns);
    }

    public function hasFilter()
    {
        return method_exists($this->model, 'scopeFilter');
    }
    public function findAndUpdate($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model->refresh();
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function deleteMany($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function findManyByColumn(string $column = 'id', array $values = [], array $relations = [])
    {
        return $this->model->with($relations)->whereIn($column, $values)->get();
    }

    public function findByFlexible(
        array $criteria,
        array $columns = ['*'],
        array $relations = [],
        ?bool $paginate = true,
        ?string $orderBy = 'id',
        ?string $direction = 'desc'
    ) {
        $builder = $this->model->query();

        foreach ($criteria as $key => $value) {
            $operator = '=';
            $isOrGroup = is_array($value) && isset($value[0]) && !isset($value['operator']);

            if (is_array($value) && isset($value['value'])) {
                $operator = $value['operator'] ?? '=';
                $value = $value['value'];
            }

            if (strpos($key, '.') !== false) {
                [$relation, $relationKey] = explode('.', $key);

                $builder->whereHas($relation, function ($query) use ($relationKey, $operator, $value, $isOrGroup) {
                    if ($isOrGroup) {
                        $query->where(function ($q) use ($relationKey, $value) {
                            foreach ($value as $v) {
                                $q->orWhere($relationKey, '=', $v);
                            }
                        });
                    } else {
                        $query->where($relationKey, $operator, $value);
                    }
                });
            } else {
                if ($isOrGroup) {
                    $builder->where(function ($q) use ($key, $value) {
                        foreach ($value as $v) {
                            $q->orWhere($key, '=', $v);
                        }
                    });
                } else {
                    $builder->where($key, $operator, $value);
                }
            }
        }

        $builder->with($relations)
            ->orderBy($orderBy, $direction)
            ->select($columns);

        return $paginate
            ? $builder->paginate(request('per_page', 15))
            : $builder->get();
    }

    public function getModel()
    {
        return $this->model->query();
    }

    /**
     * Count records matching criteria
     *
     * @param array $criteria Filter criteria
     * @return int
     */
    public function count(array $criteria = []): int
    {
        $builder = $this->model->query();

        foreach ($criteria as $key => $value) {
            $operator = '=';

            if (is_array($value)) {
                $operator = $value['operator'] ?? $operator;
                $value = $value['value'];
            }

            if (strpos($key, '.') !== false) {
                list($relation, $relationKey) = explode('.', $key);
                $builder->whereHas($relation, function ($query) use ($relationKey, $operator, $value) {
                    $query->where($relationKey, $operator, $value);
                });
            } else {
                $builder->where($key, $operator, $value);
            }
        }

        return $builder->count();
    }
}

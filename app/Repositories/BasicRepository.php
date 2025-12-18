<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Abstract repository which all other repositories inherit
 *
 * Class Repository
 *
 * @package                                  App\Repositories
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class BasicRepository
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Eloquent|Model
     */
    protected $model;

    /**
     * @throws Exception
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->init();
    }

    /**
     * Specify Model class name
     */
    abstract public function model(): string;

    /**
     * Init repository.
     */
    protected function init()
    {
    }
    /**
     * Inits model
     *
     * @throws Exception
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception(
                "Class {$this->model()} must be an "
                . "instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * {@inheritDoc}
     *
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritDoc}
     *
     * @param  array      $data
     * @param  int|string $value
     * @param  string     $attribute
     * @return Model|bool|int
     */
    public function update(array $data, $value, string $attribute = 'id')
    {
        $model = $this->model->where($attribute, '=', $value)->firstOrFail();

        $translatableFields = $model->getTranslatableAttributes();
        foreach ($data as $field => $value) {
            if (in_array($field, $translatableFields)) {
                $existingTranslations = $model->getTranslations($field);
                $newTranslations = $data[$field];
                $mergedTranslations = array_merge($existingTranslations, $newTranslations);
                $data[$field] = $mergedTranslations;
            }
        }

        $model->update($data);
        return $model->refresh();
    }

    /**
     * Upsert function for creating or updating record if exists
     *
     * @param  array $data
     * @param  array $criteria
     * @return Model|bool
     */
    public function updateOrCreate(array $data, array $criteria = [])
    {
        $model = $this->findOneBy($criteria, ['*'], false);
        if ($model) {
            $model->update($data);
            $model->refresh();
            return $model;
        }
        return $this->create($data);
    }

    /**
     * {@inheritDoc}
     *
     * @param  string $column
     * @param  string $value
     * @return int
     */
    public function delete(string $column, string $value): int
    {
        return $this->findOneBy([$column => $value])->delete();
    }

    /**
     * Summary of deleteAll
     * @param string $column
     * @param array $values
     * @return int
     */
    public function deleteAll(string $column, array $values): int
    {
        return $this->whereIn($column, $values)->delete();
    }

    /**
     * {@inheritDoc}
     *
     * @param  string $column
     * @param  array  $values
     * @return bool
     */
    public function existsBy(string $column, string $value, $operator = '='): bool
    {
        return $this->model->where($column, $operator, $value)->exists();
    }

    /**
     * {@inheritDoc}
     *
     * @param  string|int $id
     * @param  array      $columns
     * @return Model|null
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * {@inheritDoc}
     *
     * @param  array $criteria
     * @param  array $columns
     * @param  bool  $fail
     * @return Model|null
     */
    public function findOneBy(array $criteria, array $columns = ['*'], $fail = true)
    {
        $builder = $this->handleCriteria($criteria, $this->model->query());

        return $fail ? $builder->firstOrFail($columns) : $builder->first($columns);
    }

    /**
     * {@inheritDoc}
     *
     * @param  array $criteria
     * @param  array $columns
     * @param  array $relations
     * param  bool  $fail
     * @return Model|null
     */
    public function findOneByWith($criteria, array $columns = ['*'], array $relations = [], $fail = true)
    {
        $builder = $this->handleCriteria($criteria, $this->model->with($relations));

        return $fail ? $builder->firstOrFail($columns) : $builder->first($columns);
    }

    /**
     * {@inheritDoc}
     *
     * @param  array $criteria
     * @param  array $columns
     * @param  array $orderBy
     * @return Collection|Model[]|Builder
     */
    public function findBy(array $criteria, array $columns = ['*'], array $orderBy = [], int $paginate = null)
    {
        $builder = $this->handleCriteria($criteria, $this->model->query());
        $builder = $this->orderBy($builder, $orderBy);
        $builder = $builder->select($columns);

        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * {@inheritDoc}
     *
     * @param  array $criteria
     * @param  array $columns
     * @param  array $relations
     * @param  array $orderBy
     * @return Collection|Model[]|Builder
     */
    public function findByWith(array $criteria, array $columns = ['*'], array $relations = [], array $orderBy = [], int $paginate = null)
    {
        $builder = $this->handleCriteria($criteria, $this->model->with($relations));
        $builder = $this->orderBy($builder, $orderBy);
        $builder = $builder->select($columns);

        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * {@inheritDoc}
     *
     * @param  array $columns
     * @param  array $relations
     * @param  array $orderBy
     * @return Collection|Model[]|Builder
     */
    public function findAllWith(array $columns = ['*'], array $relations, array $orderBy = [], int $paginate = null)
    {
        $builder = $this->orderBy($this->model->with($relations), $orderBy);
        $builder = $builder->select($columns);

        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * {@inheritDoc}
     *
     * @param  string column
     * @param  array         $array
     * @return Collection|Model[]
     */
    public function whereIn(string $column, array $values, int $paginate = null)
    {
        $builder = $this->model->whereIn($column, $values);

        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * Summary of groupByWith
     *
     * @param array $criteria
     * @param mixed $groupByColumn
     * @param array $with
     * @param array $columns
     * @param array $orderBy
     * @param int $paginate
     * @return Collection|Model[]|Builder
     */
    public function groupByWith(array $criteria, $groupByColumn, array $with, array $columns = ['*'], array $orderBy = [], int $paginate = null)
    {
        $builder = $this->handleCriteria($criteria, $this->model->with($with)->select($columns));
        $builder = $this->orderBy($builder, $orderBy);

        $collection = $builder->get();

        $grouped = $this->groupCollectionByColumn($collection, $groupByColumn);

        return $paginate ? $this->paginateCollection($grouped, $paginate) : $grouped;
    }

    /**
     * Summary of handleCriteria
     *
     * @param array $criteria
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return Builder
     */
    public function handleCriteria(array $criteria, Builder $builder)
    {
        $criteria = $this->prepareCriteria($criteria);
        foreach ($criteria as $key => $value) {
            $operator = '=';
            if (is_array($value)) {
                $operator = strtolower($value['operator'] ?? '=');
                $value = $value['value'] ?? null;
            }

            if (str_contains($key, '.')) {
                $this->handleRelationCriteria($builder, $key, $operator, $value);
            } else {
                $this->handleDirectCriteria($builder, $key, $operator, $value);
            }
        }

        return $builder;
    }

    /**
     * Apply a direct filter on the builder (non-relation based).
     *
     * @param Builder $builder
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return void
     */
    protected function handleDirectCriteria(Builder $builder, string $key, string $operator, $value): void
    {
        if (str_contains($key, '->')) {
            [$column, $jsonKey] = explode('->', $key, 2);
            $jsonPath = '$.' . $jsonKey;

            $builder->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`$column`, ?))) LIKE ?", [
                $jsonPath,
                '%' . strtolower($value) . '%',
            ]);
        } elseif ($operator === 'like') {
            $builder->whereRaw("LOWER(`$key`) LIKE ?", ['%' . strtolower($value) . '%']);
        } elseif ($operator === 'in') {
            $builder->whereIn($key, $value);
        } elseif ($operator === 'not in') {
            $builder->whereNotIn($key, $value);
        } else {
            $builder->where($key, $operator, $value);
        }
    }

    /**
     * Apply a filter on a related model using whereHas.
     *
     * @param Builder $builder
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return void
     */
    protected function handleRelationCriteria(Builder $builder, string $key, string $operator, $value): void
    {
        [$relation, $column] = explode('.', $key, 2);

        $builder->whereHas($relation, function ($query) use ($column, $operator, $value) {
            if (str_contains($column, '->')) {
                [$jsonCol, $jsonKey] = explode('->', $column, 2);
                $jsonPath = '$.' . $jsonKey;

                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`$jsonCol`, ?))) LIKE ?", [
                    $jsonPath,
                    '%' . strtolower($value) . '%',
                ]);
            } elseif ($operator === 'like') {
                $query->whereRaw("LOWER(`$column`) LIKE ?", ['%' . strtolower($value) . '%']);
            } elseif ($operator === 'in') {
                $query->whereIn($column, $value);
            } elseif ($operator === 'not in') {
                $query->whereNotIn($column, $value);
            } else {
                $query->where($column, $operator, $value);
            }
        });
    }

    /**
     * Prepare the criteria array by flattening "filters" into the main array,
     * while keeping dot notation for related keys.
     *
     * Example:
     * Input: ['filters' => ['roles.name' => ['operator' => 'like', 'value' => 'admin']]]
     * Output: ['roles.name' => ['operator' => 'like', 'value' => 'admin']]
     *
     * @param array $criteria
     * @return array
     */
    private function prepareCriteria(array $criteria): array
    {
        $flattened = collect($criteria['filters'] ?? [])
            ->mapWithKeys(function ($value, $key) {
                return [$key => $value];
            })
            ->toArray();

        return array_merge(
            Arr::except($criteria, 'filters'),
            $flattened
        );
    }

    /**
     * Apply ordering to the query builder.
     *
     * @param  Builder $builder
     * @param  array   $orderBy
     * @return Builder
     */
    protected function orderBy(Builder $builder, array $orderBy): Builder
    {
        foreach ($orderBy as $column => $direction) {
            $builder->orderBy($column, $direction);
        }

        return $builder;
    }

    /**
     * Summary of groupCollectionByColumn
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param string $column
     * @return Collection
     */
    protected function groupCollectionByColumn(Collection $collection, string $column)
    {
        $grouped = $collection->groupBy($column);

        $castedEnums = $this->model->getCasts();
        $isEnum = isset($castedEnums[$column]) && enum_exists($castedEnums[$column]);

        return $grouped->map(function ($items, $groupValue) use ($isEnum, $castedEnums, $column) {
            if ($isEnum) {
                $enumClass = $castedEnums[$column];
                $enum = $enumClass::from($groupValue);

                return [
                    'value' => $enum->value,
                    'label' => $enum->lang(),
                    'values' => $items->values(),
                ];
            }

            return [
                'value' => $groupValue,
                'label' => $groupValue,
                'values' => $items->values(),
            ];
        })->values();
    }

    /**
     * Summary of paginateCollection
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginateCollection(Collection $collection, int $perPage)
    {
        $page = request('page', 1);
        $items = $collection->forPage($page, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Get query builder instance
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * Count records
     *
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria = []): int
    {
        if (empty($criteria)) {
            return $this->model->count();
        }

        $builder = $this->handleCriteria($criteria, $this->model->query());
        return $builder->count();
    }

    /**
     * Find all records with optional pagination
     *
     * @param array $columns
     * @param array $orderBy
     * @param int|null $paginate
     * @return Collection|Model[]|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(array $columns = ['*'], array $orderBy = [], int $paginate = null)
    {
        $builder = $this->orderBy($this->model->query(), $orderBy);
        $builder = $builder->select($columns);

        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * Get first record matching criteria
     *
     * @param array $criteria
     * @return Model|null
     */
    public function first(array $criteria = [])
    {
        if (empty($criteria)) {
            return $this->model->first();
        }

        return $this->handleCriteria($criteria, $this->model->query())->first();
    }

    /**
     * Get latest records
     *
     * @param int $limit
     * @param array $criteria
     * @return Collection
     */
    public function latest(int $limit = 10, array $criteria = [])
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->latest()->limit($limit)->get();
    }

    /**
     * Bulk insert records (high performance)
     *
     * @param array $data
     * @return bool
     */
    public function bulkInsert(array $data): bool
    {
        return $this->model->insert($data);
    }

    /**
     * Bulk update records
     *
     * @param array $criteria
     * @param array $data
     * @return int
     */
    public function bulkUpdate(array $criteria, array $data): int
    {
        $builder = $this->handleCriteria($criteria, $this->model->query());
        return $builder->update($data);
    }

    /**
     * Chunk large datasets for memory efficiency
     *
     * @param int $count
     * @param callable $callback
     * @param array $criteria
     * @return bool
     */
    public function chunk(int $count, callable $callback, array $criteria = []): bool
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->chunk($count, $callback);
    }

    /**
     * Advanced search across multiple columns
     *
     * @param string $searchTerm
     * @param array $columns
     * @param array $criteria
     * @param int|null $paginate
     * @return Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function search(string $searchTerm, array $columns, array $criteria = [], int $paginate = null)
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        $builder->where(function ($query) use ($searchTerm, $columns) {
            foreach ($columns as $column) {
                if (str_contains($column, '->')) {
                    // Handle JSON columns
                    [$jsonColumn, $jsonKey] = explode('->', $column, 2);
                    $jsonPath = '$.' . $jsonKey;
                    $query->orWhereRaw(
                        "LOWER(JSON_UNQUOTE(JSON_EXTRACT(`$jsonColumn`, ?))) LIKE ?",
                        [$jsonPath, '%' . strtolower($searchTerm) . '%']
                    );
                } else {
                    $query->orWhereRaw("LOWER(`$column`) LIKE ?", ['%' . strtolower($searchTerm) . '%']);
                }
            }
        });

        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * Pluck specific column values
     *
     * @param string $column
     * @param string|null $key
     * @param array $criteria
     * @return \Illuminate\Support\Collection
     */
    public function pluck(string $column, string $key = null, array $criteria = [])
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->pluck($column, $key);
    }

    /**
     * Get sum of a column
     *
     * @param string $column
     * @param array $criteria
     * @return mixed
     */
    public function sum(string $column, array $criteria = [])
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->sum($column);
    }

    /**
     * Get average of a column
     *
     * @param string $column
     * @param array $criteria
     * @return mixed
     */
    public function avg(string $column, array $criteria = [])
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->avg($column);
    }

    /**
     * Get max value of a column
     *
     * @param string $column
     * @param array $criteria
     * @return mixed
     */
    public function max(string $column, array $criteria = [])
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->max($column);
    }

    /**
     * Get min value of a column
     *
     * @param string $column
     * @param array $criteria
     * @return mixed
     */
    public function min(string $column, array $criteria = [])
    {
        $builder = empty($criteria)
            ? $this->model->query()
            : $this->handleCriteria($criteria, $this->model->query());

        return $builder->min($column);
    }

    /**
     * Increment a column value
     *
     * @param mixed $id
     * @param string $column
     * @param int $amount
     * @return int
     */
    public function increment($id, string $column, int $amount = 1): int
    {
        return $this->model->where('id', $id)->increment($column, $amount);
    }

    /**
     * Decrement a column value
     *
     * @param mixed $id
     * @param string $column
     * @param int $amount
     * @return int
     */
    public function decrement($id, string $column, int $amount = 1): int
    {
        return $this->model->where('id', $id)->decrement($column, $amount);
    }

    /**
     * Soft delete (if model uses SoftDeletes trait)
     *
     * @param mixed $id
     * @return bool|null
     */
    public function softDelete($id)
    {
        return $this->model->find($id)?->delete();
    }

    /**
     * Restore soft deleted record
     *
     * @param mixed $id
     * @return bool|null
     */
    public function restore($id)
    {
        return $this->model->withTrashed()->find($id)?->restore();
    }

    /**
     * Force delete (permanent)
     *
     * @param mixed $id
     * @return bool|null
     */
    public function forceDelete($id)
    {
        return $this->model->withTrashed()->find($id)?->forceDelete();
    }

    /**
     * Get trashed records only
     *
     * @param int|null $paginate
     * @return Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function onlyTrashed(int $paginate = null)
    {
        $builder = $this->model->onlyTrashed();
        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }

    /**
     * Get records with trashed
     *
     * @param int|null $paginate
     * @return Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function withTrashed(int $paginate = null)
    {
        $builder = $this->model->withTrashed();
        return $paginate ? $builder->paginate($paginate) : $builder->get();
    }
}

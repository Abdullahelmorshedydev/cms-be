<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base Service Class
 *
 * This abstract class provides common CRUD operations and business logic patterns
 * for all service classes in the application. It follows the Service Layer pattern
 * to separate business logic from controllers and repositories.
 *
 * Architecture Pattern: Service Layer (part of Repository-Service-Controller pattern)
 *
 * Responsibilities:
 * - Handle business logic and validation
 * - Coordinate between repositories and controllers
 * - Manage database transactions
 * - Format API responses consistently
 *
 * @package App\Services
 */
abstract class BaseService
{
    /**
     * BaseService constructor.
     *
     * Injects the repository dependency using dependency injection.
     * This follows the Dependency Inversion Principle (SOLID).
     *
     * @param mixed $repository The repository instance for data access
     */
    public function __construct(protected $repository)
    {
    }

    /**
     * Retrieve paginated list of records
     *
     * This method handles listing with pagination, filtering, sorting, and eager loading.
     * It provides a consistent API response format across all endpoints.
     *
     * @param array $data Filter and pagination parameters
     * @param array $with Relationships to eager load (for N+1 query prevention)
     * @param array $columns Columns to select from database
     * @param array $order Default sorting order ['column' => 'direction']
     * @param int $limit Default pagination limit
     * @return array Standardized API response with pagination metadata
     */
    public function index($data, $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        // Build query with filters, relationships, and sorting
        $results = $this->repository->findByWith(
            $data,
            $columns,
            $with,
            [$data['sort_by'] ?? key($order) => $data['sort_order'] ?? $order[key($order)]],
            $data['limit'] ?? $limit
        );

        // Check if results is a paginator or collection
        if ($results instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            // Return standardized response with pagination metadata
            return returnData([], Response::HTTP_OK, [
                'data' => $results->items(),
            ], __('custom.messages.retrieved_success'), [
                'current_page' => $results->currentPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'last_page' => $results->lastPage(),
                'next_page_url' => $results->nextPageUrl(),
                'prev_page_url' => $results->previousPageUrl(),
            ]);
        } else {
            // Handle Collection case
            $items = $results instanceof \Illuminate\Database\Eloquent\Collection 
                ? $results->toArray() 
                : (is_array($results) ? $results : [$results]);
            
            return returnData([], Response::HTTP_OK, [
                'data' => $items,
            ], __('custom.messages.retrieved_success'), [
                'current_page' => 1,
                'total' => count($items),
                'per_page' => count($items),
                'last_page' => 1,
                'next_page_url' => null,
                'prev_page_url' => null,
            ]);
        }
    }

    /**
     * Create a new record
     *
     * Handles record creation with database transaction support for data integrity.
     * Optional callback allows for additional operations after creation (e.g., relationships, events).
     *
     * @param array $data Record data to be created
     * @param callable|null $callback Optional callback function executed after successful creation
     * @return array Standardized API response with created record
     */
    public function store($data, callable $callback = null)
    {
        try {
            // Start transaction to ensure data consistency
            DB::beginTransaction();

            // Create the record through repository
            $model = $this->repository->create($data);

            // Execute optional callback for additional operations (e.g., relationships, events)
            if ($callback) {
                $callback($model, $data);
            }

            // Commit transaction if all operations succeed
            DB::commit();

            // Return success response with refreshed model (includes any auto-generated fields)
            return returnData(
                [],
                Response::HTTP_CREATED,
                [
                    'record' => $model->refresh(),
                ],
                __('custom.messages.created_success')
            );
        } catch (\Exception $e) {
            // Rollback transaction on any error to maintain data integrity
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Retrieve a single record by key-value pair
     *
     * Fetches a single record with optional eager loading of relationships
     * to prevent N+1 query problems.
     *
     * @param string $key Column name to search by
     * @param mixed $value Value to match
     * @param array $with Relationships to eager load
     * @return array Standardized API response with record
     */
    public function show($key, $value, $with = [])
    {
        try {
            $model = $this->repository->findOneByWith([$key => $value], ['*'], $with);

            // If model is null and fail was true, firstOrFail would have thrown ModelNotFoundException
            // But if fail was false, we need to check here
            if (!$model) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                    'No query results for model [' . get_class($this->repository->model()) . ']'
                );
            }

            return returnData(
                [],
                Response::HTTP_OK,
                [
                    'record' => $model
                ],
                __('custom.messages.retrieved_success')
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Re-throw ModelNotFoundException so it can be handled by the exception handler
            throw $e;
        } catch (\Exception $e) {
            // For other exceptions, wrap and re-throw or return error response
            throw $e;
        }
    }

    /**
     * Update an existing record
     *
     * Updates a record identified by key-value pair with transaction support.
     * Optional callback allows for additional operations after update.
     *
     * @param array $data Data to update
     * @param mixed $value Value to identify the record
     * @param string $key Column name to identify the record (default: 'id')
     * @param callable|null $callback Optional callback function executed after successful update
     * @return array Standardized API response with updated record
     */
    public function update($data, $value, $key = 'id', callable $callback = null)
    {
        try {
            // Start transaction for data consistency
            DB::beginTransaction();

            // Update the record
            $this->repository->update($data, $value, $key);

            // Fetch updated record
            $model = $this->repository->findOneBy([$key => $value]);

            // Execute optional callback for additional operations
            if ($callback) {
                $callback($model, $data);
            }

            // Commit transaction
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                [
                    'record' => $model->refresh(),
                ],
                __('custom.messages.updated_success')
            );
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Delete a record
     *
     * Permanently deletes a record with transaction support.
     * Optional callback allows for cleanup operations before deletion.
     *
     * @param string $key Column name to identify the record
     * @param mixed $value Value to identify the record
     * @param callable|null $beforeDelete Optional callback executed before deletion (e.g., cleanup)
     * @return array Standardized API response
     */
    public function destroy($key, $value, callable $beforeDelete = null)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Fetch record before deletion (for cleanup operations)
            $model = $this->repository->findOneBy([$key => $value]);

            // Execute optional cleanup callback before deletion
            if ($beforeDelete) {
                $beforeDelete($model);
            }

            // Delete the record
            $this->repository->delete($key, $value);

            // Commit transaction
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                [
                    'deleted' => true
                ],
                __('custom.messages.deleted_success')
            );
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            return handleException($e);
        }
    }

    public function destroyAll(array $ids, callable $beforeDelete = null)
    {
        try {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $model = $this->repository->findOneBy(['id' => $id]);
                if ($beforeDelete) {
                    $beforeDelete($model);
                }
                $this->repository->delete('id', $id);
            }
            DB::commit();
            return returnData(
                [],
                Response::HTTP_OK,
                [
                    'deleted' => true
                ],
                __('custom.messages.deleted_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Get all records without pagination
     *
     * Retrieves all records matching criteria without pagination.
     * Use with caution on large datasets - consider using index() with pagination instead.
     *
     * @param array $with Relationships to eager load (prevents N+1 queries)
     * @param array $columns Columns to select from database
     * @param array $order Sorting order ['column' => 'direction']
     * @return array Standardized API response with all records
     */
    public function all(array $with = [], array $columns = ['*'], array $order = ['id' => 'DESC'])
    {
        $results = $this->repository->findAllWith($columns, $with, $order);

        return returnData(
            [],
            Response::HTTP_OK,
            ['data' => $results],
            __('custom.messages.retrieved_success')
        );
    }

    /**
     * Get count of records matching criteria
     *
     * Efficiently counts records without loading them into memory.
     * Useful for statistics and pagination metadata.
     *
     * @param array $criteria Filter criteria for counting
     * @return array Standardized API response with count
     */
    public function count(array $criteria = [])
    {
        $count = $this->repository->count($criteria);

        return returnData(
            [],
            Response::HTTP_OK,
            ['count' => $count],
            __('custom.messages.retrieved_success')
        );
    }

    /**
     * Search records across multiple columns
     *
     * Performs full-text search across specified columns with pagination.
     * Combines search results with additional filter criteria.
     *
     * @param string $searchTerm Search query string
     * @param array $columns Columns to search in
     * @param array $criteria Additional filter criteria
     * @param int $limit Pagination limit
     * @return array Standardized API response with search results and pagination
     */
    public function search(string $searchTerm, array $columns, array $criteria = [], int $limit = 15)
    {
        $results = $this->repository->search($searchTerm, $columns, $criteria, $limit);

        // Check if results is a paginator or collection
        if ($results instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            return returnData(
                [],
                Response::HTTP_OK,
                [
                    'data' => $results->items(),
                ],
                __('custom.messages.retrieved_success'),
                [
                    'current_page' => $results->currentPage(),
                    'total' => $results->total(),
                    'per_page' => $results->perPage(),
                    'last_page' => $results->lastPage(),
                    'next_page_url' => $results->nextPageUrl(),
                    'prev_page_url' => $results->previousPageUrl(),
                ]
            );
        } else {
            // Handle Collection case
            $items = $results instanceof \Illuminate\Database\Eloquent\Collection 
                ? $results->toArray() 
                : (is_array($results) ? $results : [$results]);
            
            return returnData(
                [],
                Response::HTTP_OK,
                [
                    'data' => $items,
                ],
                __('custom.messages.retrieved_success'),
                [
                    'current_page' => 1,
                    'total' => count($items),
                    'per_page' => count($items),
                    'last_page' => 1,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ]
            );
        }
    }

    /**
     * Bulk update records
     *
     * @param array $criteria
     * @param array $data
     * @return array
     */
    public function bulkUpdate(array $criteria, array $data)
    {
        try {
            DB::beginTransaction();
            $affected = $this->repository->bulkUpdate($criteria, $data);
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                ['affected' => $affected],
                __('custom.messages.updated_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Bulk delete records
     *
     * @param string $column
     * @param array $values
     * @return array
     */
    public function bulkDelete(string $column, array $values)
    {
        try {
            DB::beginTransaction();
            $deleted = $this->repository->deleteAll($column, $values);
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                ['deleted' => $deleted],
                __('custom.messages.deleted_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Toggle a boolean field
     *
     * @param mixed $id
     * @param string $field
     * @return array
     */
    public function toggle($id, string $field)
    {
        try {
            DB::beginTransaction();
            $model = $this->repository->find($id);
            $model->update([$field => !$model->$field]);
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                ['record' => $model->refresh()],
                __('custom.messages.updated_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Get latest records
     *
     * @param int $limit
     * @param array $criteria
     * @param array $with
     * @return array
     */
    public function latest(int $limit = 10, array $criteria = [], array $with = [])
    {
        $results = $this->repository->latest($limit, $criteria);

        return returnData(
            [],
            Response::HTTP_OK,
            ['data' => $results],
            __('custom.messages.retrieved_success')
        );
    }

    /**
     * Update or create a record
     *
     * @param array $criteria
     * @param array $data
     * @return array
     */
    public function updateOrCreate(array $criteria, array $data)
    {
        try {
            DB::beginTransaction();
            $model = $this->repository->updateOrCreate($data, $criteria);
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                ['record' => $model],
                __('custom.messages.updated_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Get statistics (override in child classes for specific stats)
     *
     * @param array $criteria
     * @return array
     */
    public function getStatistics(array $criteria = [])
    {
        $total = $this->repository->count($criteria);

        return returnData(
            [],
            Response::HTTP_OK,
            [
                'total' => $total,
            ],
            __('custom.messages.retrieved_success')
        );
    }

    /**
     * Soft delete a record
     *
     * @param mixed $id
     * @return array
     */
    public function softDelete($id)
    {
        try {
            DB::beginTransaction();
            $this->repository->softDelete($id);
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                ['deleted' => true],
                __('custom.messages.deleted_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Restore a soft deleted record
     *
     * @param mixed $id
     * @return array
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $this->repository->restore($id);
            $model = $this->repository->find($id);
            DB::commit();

            return returnData(
                [],
                Response::HTTP_OK,
                ['record' => $model],
                __('custom.messages.updated_success')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return handleException($e);
        }
    }

    /**
     * Export data (override in child classes for specific export logic)
     *
     * @param array $criteria
     * @param array $columns
     * @return string File path
     */
    public function export(array $criteria = [], array $columns = ['*'])
    {
        // This is a basic implementation. Override in child services for custom export
        $data = $this->repository->findBy($criteria, $columns);

        $filename = class_basename($this->repository->model()) . '_export_' . now()->format('Y_m_d_His') . '.csv';
        $filePath = storage_path('app/exports/' . $filename);

        // Create exports directory if it doesn't exist
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $file = fopen($filePath, 'w');

        // Add CSV headers
        if ($data->isNotEmpty()) {
            fputcsv($file, array_keys($data->first()->toArray()));
        }

        // Add data rows
        foreach ($data as $row) {
            fputcsv($file, $row->toArray());
        }

        fclose($file);

        return $filePath;
    }
}

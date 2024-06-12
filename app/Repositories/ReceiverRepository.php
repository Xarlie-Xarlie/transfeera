<?php

namespace App\Repositories;

use App\Models\Receiver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ReceiverRepository implements ReceiverRepositoryInterface
{
    protected $filterDefinitions = [
        'status' => '=',
        'name' => 'like',
        'pix_key_type' => '=',
        'pix_key' => 'like'
    ];

    /**
     * Build the filters to query receivers.
     *
     * @param array $filters Array containing the filters.
     * @param Builder $query The initial query builder instance.
     * @return Builder The query builder instance with applied filters.
     */
    protected function buildFilters(array $filters, Builder $query): Builder
    {
        $filterDefinitions = $this->filterDefinitions;

        return array_reduce(array_keys($filters), function ($query, $field) use ($filters, $filterDefinitions) {
            if (isset($filters[$field])) {
                $condition = $filterDefinitions[$field] === 'like' ? 'like' : '=';
                $value = $condition === 'like' ? '%' . $filters[$field] . '%' : $filters[$field];
                return $query->where($field, $condition, $value);
            }
            return $query;
        }, $query);
    }

    /**
     * Get all receivers with filters and pagination.
     *
     * @param array $filters Filters to apply to the query.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator Paginated result of receivers.
     */
    public function all(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Receiver::query();
        $query = $this->buildFilters($filters, $query);

        return $query->paginate($perPage);
    }

    /**
     * Create a new receiver.
     *
     * @param array $data Data for the new receiver.
     * @return Model The created receiver.
     */
    public function create(array $data): Model
    {
        return Receiver::create($data);
    }

    /**
     * Find a receiver by ID.
     *
     * @param int $id ID of the receiver to find.
     * @return Model|null The found receiver or null.
     */
    public function find(int $id): ?Model
    {
        return Receiver::find($id);
    }

    /**
     * Update a receiver by ID.
     *
     * @param int $id ID of the receiver to update.
     * @param array $data Data to update the receiver with.
     * @return Model|null The updated receiver or null if not found.
     */
    public function update(int $id, array $data): ?Model
    {
        $receiver = Receiver::find($id);
        if ($receiver) {
            $receiver->update($data);
            return $receiver;
        }
        return null;
    }

    /**
     * Delete a receiver by ID.
     *
     * @param int $id ID of the receiver to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        $receiver = Receiver::find($id);
        if ($receiver) {
            $receiver->delete();
            return true;
        }
        return false;
    }

    /**
     * Delete multiple receivers by IDs.
     *
     * @param array $ids Array of IDs of the receivers to delete.
     * @return bool The result of the deletion operation.
     */
    public function deleteMany(array $ids): bool
    {
        return Receiver::whereIn('id', $ids)->delete();
    }
}

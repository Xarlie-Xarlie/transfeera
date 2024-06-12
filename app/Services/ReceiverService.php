<?php

namespace App\Services;

use App\Repositories\ReceiverRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ReceiverService
{
    protected $receiverRepository;

    /**
     * ReceiverService constructor.
     *
     * @param ReceiverRepositoryInterface $receiverRepository
     */
    public function __construct(ReceiverRepositoryInterface $receiverRepository)
    {
        $this->receiverRepository = $receiverRepository;
    }

    /**
     * Get all receivers with filters and pagination.
     *
     * @param array $filters Filters to apply to the query.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator or Collection Paginated result of receivers .
     */
    public function getAll(array $filters, int $perPage): LengthAwarePaginator | Collection
    {
        return $this->receiverRepository->all($filters, $perPage);
    }

    /**
     * Create a new receiver.
     *
     * @param array $data Data for the new receiver.
     * @return Model The created receiver.
     */
    public function create(array $data): Model
    {
        $data['status'] = 'rascunho';
        return $this->receiverRepository->create($data);
    }

    /**
     * Find a receiver by ID.
     *
     * @param int $id ID of the receiver to find.
     * @return Model|null The found receiver or null.
     */
    public function find(int $id): ?Model
    {
        return $this->receiverRepository->find($id);
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
        $receiver = $this->receiverRepository->find($id);
        if (!$receiver) {
            return null;
        }

        if ($receiver->status == 'validado') {
            $data = isset($data['email']) ? ['email' => $data['email']] : [];
        }

        return $this->receiverRepository->update($id, $data);
    }

    /**
     * Delete a receiver by ID.
     *
     * @param int $id ID of the receiver to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        return $this->receiverRepository->delete($id);
    }

    /**
     * Delete multiple receivers by IDs.
     *
     * @param array $ids Array of IDs of the receivers to delete.
     * @return bool The result of the deletion operation.
     */
    public function deleteMany(array $ids): bool
    {
        return $this->receiverRepository->deleteMany($ids);
    }
}

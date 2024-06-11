<?php

namespace App\Services;

use App\Repositories\ReceiverRepositoryInterface;

class ReceiverService
{
    protected $receiverRepository;

    public function __construct(ReceiverRepositoryInterface $receiverRepository)
    {
        $this->receiverRepository = $receiverRepository;
    }

    public function getAll($filters, $perPage)
    {
        return $this->receiverRepository->all($filters, $perPage);
    }

    public function create(array $data)
    {
        $data['status'] = 'rascunho';
        return $this->receiverRepository->create($data);
    }

    public function find($id)
    {
        return $this->receiverRepository->find($id);
    }

    public function update($id, array $data)
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

    public function delete($id)
    {
        return $this->receiverRepository->delete($id);
    }

    public function deleteMany(array $ids)
    {
        return $this->receiverRepository->deleteMany($ids);
    }
}

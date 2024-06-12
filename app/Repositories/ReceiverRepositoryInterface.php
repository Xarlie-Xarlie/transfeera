<?php

namespace App\Repositories;

interface ReceiverRepositoryInterface
{
    public function all(array $filters, int $perPage);
    public function create(array $data);
    public function find(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function deleteMany(array $ids);
}

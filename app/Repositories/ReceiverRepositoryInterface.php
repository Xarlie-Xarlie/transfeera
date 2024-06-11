<?php

namespace App\Repositories;

interface ReceiverRepositoryInterface
{
    public function all($filters, $perPage);
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function deleteMany(array $ids);
}

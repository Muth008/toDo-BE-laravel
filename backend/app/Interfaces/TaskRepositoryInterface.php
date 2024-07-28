<?php

namespace App\Interfaces;

interface TaskRepositoryInterface extends ApiRepositoryInterface
{
    public function index(array $filters = null, int $perPage = null, int $page = null);
    public function count(array $filters): int;
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
}

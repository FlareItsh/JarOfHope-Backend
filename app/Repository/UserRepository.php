<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = User::latest();

        if (!empty($filters['search'])) {
            $query->where('nickname', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    public function create(array $payload)
    {
        return User::create($payload);
    }

    public function findByUuid(string $uuid)
    {
        $query = User::query();
        return $query->where('uuid', $uuid)->first();
    }

    public function findByField(string $field, $value)
    {
        $query = User::query();
        return $query->where($field, $value)->first();
    }

    public function update(string $uuid, array $payload)
    {
        $model = $this->findByUuid($uuid);
        $model->update($payload);
        return $model;
    }

    public function delete(string $uuid)
    {
        $query = User::query();
        $model = $query->where('uuid', $uuid)->first();
        $model->delete();
        return $model;
    }

    public function restore(string $uuid)
    {
        $query = User::query();
        $model = $query->where('uuid', $uuid)->first();
        $model->restore();
        return $model;
    }
}

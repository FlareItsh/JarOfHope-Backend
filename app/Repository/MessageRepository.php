<?php

namespace App\Repository;

use App\Models\Message;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MessageRepository
{
    public function paginate(int $perPage = 15)
    {
        return Message::latest()->paginate($perPage);
    }

    public function create(array $payload)
    {
        return Message::create($payload);
    }

    public function findByUuid(string $uuid)
    {
        return Message::where('uuid', $uuid)->firstOrFail();
    }

    public function findByField(string $field, $value)
    {
        return Message::where($field, $value)->firstOrFail();
    }

    public function update(string $uuid, array $payload)
    {
        $model = $this->findByUuid($uuid);
        $model->update($payload);
        return $model;
    }

    public function delete(string $uuid)
    {
        $model = $this->findByUuid($uuid);
        return $model->delete();
    }

    public function restore(string $uuid)
    {
        $model = Message::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $model->restore();
        return $model;
    }
}
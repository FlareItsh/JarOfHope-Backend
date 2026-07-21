<?php

namespace App\Repository;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttachmentRepository
{
    public function paginate(int $perPage = 15)
    {
        return Attachment::latest()->paginate($perPage);
    }

    public function create(array $payload)
    {
        return Attachment::create($payload);
    }

    public function findByUuid(string $uuid)
    {
        return Attachment::where('uuid', $uuid)->firstOrFail();
    }

    public function findByField(string $field, $value)
    {
        return Attachment::where($field, $value)->firstOrFail();
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
        $model = Attachment::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $model->restore();
        return $model;
    }
}
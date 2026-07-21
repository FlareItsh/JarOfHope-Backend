<?php

namespace App\Service;

use App\Repository\AttachmentRepository;
use App\Http\Resources\AttachmentResource;

class AttachmentService
{
    private AttachmentRepository $attachmentRepository;

    public function __construct(AttachmentRepository $attachmentRepository) 
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function listAttachment(int $perPage = 15)
    {
        $collection = $this->attachmentRepository->paginate($perPage);
        return AttachmentResource::collection($collection);
    }

    public function createAttachment(array $payload)
    {
        $model = $this->attachmentRepository->create($payload);
        return new AttachmentResource($model);
    }

    public function getAttachment(string $uuid)
    {
        $model = $this->attachmentRepository->findByUuid($uuid);
        return new AttachmentResource($model);
    }

    public function getAttachmentByField(string $field, $value)
    {
        $model = $this->attachmentRepository->findByField($field, $value);
        return new AttachmentResource($model);
    }

    public function updateAttachment(string $uuid, array $payload)
    {
        $model = $this->attachmentRepository->update($uuid, $payload);
        return new AttachmentResource($model);
    }

    public function deleteAttachment(string $uuid)
    {
        $this->attachmentRepository->delete($uuid);
        return true;
    }

    public function restoreAttachment(string $uuid)
    {
        $model = $this->attachmentRepository->restore($uuid);
        return new AttachmentResource($model);
    }
}
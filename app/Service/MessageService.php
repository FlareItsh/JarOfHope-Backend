<?php

namespace App\Service;

use App\Repository\MessageRepository;
use App\Http\Resources\MessageResource;

class MessageService
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository) 
    {
        $this->messageRepository = $messageRepository;
    }

    public function listMessage(int $perPage = 15)
    {
        $collection = $this->messageRepository->paginate($perPage);
        return MessageResource::collection($collection);
    }

    public function createMessage(array $payload)
    {
        // Extract attachments from the payload if they exist
        $attachments = $payload['attachments'] ?? [];
        unset($payload['attachments']); // Remove from payload so Eloquent doesn't complain

        // Create the message
        $model = $this->messageRepository->create($payload);

        // If there are attachments, save them using the relationship
        if (!empty($attachments)) {
            $model->attachments()->createMany($attachments);
        }

        // Load the attachments so they appear in the returned Resource
        $model->load('attachments');

        return new MessageResource($model);
    }

    public function getMessage(string $uuid)
    {
        $model = $this->messageRepository->findByUuid($uuid);
        return new MessageResource($model);
    }

    public function getMessageByField(string $field, $value)
    {
        $model = $this->messageRepository->findByField($field, $value);
        return new MessageResource($model);
    }

    public function updateMessage(string $uuid, array $payload)
    {
        $model = $this->messageRepository->update($uuid, $payload);
        return new MessageResource($model);
    }

    public function deleteMessage(string $uuid)
    {
        $this->messageRepository->delete($uuid);
        return true;
    }

    public function restoreMessage(string $uuid)
    {
        $model = $this->messageRepository->restore($uuid);
        return new MessageResource($model);
    }
}
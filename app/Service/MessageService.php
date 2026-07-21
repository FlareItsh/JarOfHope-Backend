<?php

namespace App\Service;

use App\Repository\MessageRepository;
use App\Http\Resources\MessageResource;
use App\Repository\UserRepository;

class MessageService
{
    private MessageRepository $messageRepository;
    private UserRepository $userRepository;

    public function __construct(MessageRepository $messageRepository, UserRepository $userRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    public function listMessage(int $perPage = 15)
    {
        $collection = $this->messageRepository->paginate($perPage);
        return MessageResource::collection($collection);
    }

    public function listUserMessages(string $user_uuid, int $perPage = 15)
    {
        $user = $this->userRepository->findByUuid($user_uuid);
        $messages = $this->messageRepository->paginateByField('user_uuid', $user->uuid, $perPage);
        return MessageResource::collection($messages);
    }

    public function createMessage(array $payload)
    {
        // Extract attachments from the payload if they exist
        $attachments = $payload['attachments'] ?? [];
        unset($payload['attachments']); // Remove from payload so Eloquent doesn't complain

        // Check for user account details
        $nickname = $payload['nickname'] ?? null;
        $password = $payload['password'] ?? null;
        
        $token = null;

        if ($nickname) {
            unset($payload['nickname']);
            unset($payload['password']);

            $user = $this->userRepository->findByField('nickname', $nickname);
            
            if (!$user) {
                $hasPassword = !empty($password);
                $passwordToUse = $hasPassword ? $password : \Illuminate\Support\Str::random(16);
                
                $user = $this->userRepository->create([
                    'nickname' => $nickname,
                    'password' => $passwordToUse,
                    'role' => 'user'
                ]);

                if ($hasPassword) {
                    $token = $user->createToken($user->nickname)->plainTextToken;
                }
            }

            $payload['user_uuid'] = $user->uuid;
        }

        // Create the message
        $model = $this->messageRepository->create($payload);

        // If there are attachments, save them using the relationship
        if (!empty($attachments)) {
            $model->attachments()->createMany($attachments);
        }

        // Load the attachments so they appear in the returned Resource
        $model->load('attachments');

        $response = new MessageResource($model);

        if ($token) {
            return response()->json([
                'message' => clone $response,
                'token' => $token
            ]);
        }

        return $response;
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

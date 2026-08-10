<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Repository\UserRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loginUser(object $payload)
    {

        try {
            $validated = $payload->validate([
                'nickname' => 'required|string',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            // 1. Both are missing
            if ($errors->has('nickname') && $errors->has('password')) {
                return response()->json(['message' => 'Nickname and Password fields are required'], 400);
            }

            // 2. Only nickname is missing
            if ($errors->has('nickname')) {
                return response()->json(['message' => 'Nickname field is required'], 400);
            }

            // 3. Only password is missing
            if ($errors->has('password')) {
                return response()->json(['message' => 'Password field is required'], 400);
            }

            // Fallback for other validation rules (like if they are not strings)
            return response()->json(['message' => $errors->first()], 400);
        }

        // If it passes, your validated data is ready:
        $nickname = $validated['nickname'];
        $password = $validated['password'];


        $user = $this->userRepository->findByField('nickname', $payload->nickname);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        if (! Hash::check($payload->password, $user->password)) {
            return response()->json(['message' => 'Invalid password'], 401);
        }

        $token = $user->createToken($user->nickname)->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    public function logoutUser(object $user)
    {
        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function listUser(int $perPage = 15, array $filters = [])
    {
        $collection = $this->userRepository->paginate($perPage, $filters);

        return UserResource::collection($collection);
    }

    public function createUser(array $payload)
    {
        $model = $this->userRepository->create($payload);

        return new UserResource($model);
    }

    public function getUser(string $uuid)
    {
        $model = $this->userRepository->findByUuid($uuid);

        return new UserResource($model);
    }

    public function getUserByField(string $field, $value)
    {
        $model = $this->userRepository->findByField($field, $value);

        return new UserResource($model);
    }

    public function updateUser(string $uuid, array $payload)
    {
        $model = $this->userRepository->update($uuid, $payload);

        return new UserResource($model);
    }

    public function deleteUser(string $uuid)
    {
        $this->userRepository->delete($uuid);

        return true;
    }

    public function restoreUser(string $uuid)
    {
        $model = $this->userRepository->restore($uuid);

        return new UserResource($model);
    }
}

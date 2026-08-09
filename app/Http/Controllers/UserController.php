<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        return $this->userService->listUser($request->input('per_page', 15));
    }

    public function store(Request $request)
    {
        return $this->userService->createUser($request->all());
    }

    public function show(string $uuid)
    {
        return $this->userService->getUser($uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->userService->updateUser($uuid, $request->all());
    }

    public function destroy(string $uuid)
    {
        $this->userService->deleteUser($uuid);

        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    public function restore(string $uuid)
    {
        return $this->userService->restoreUser($uuid);
    }
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nickname' => 'sometimes|required|string|max:255',
            'current_password' => 'required_with:password|current_password',
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        if (isset($validated['nickname'])) {
            $user->nickname = $validated['nickname'];
        }

        if (!empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
}

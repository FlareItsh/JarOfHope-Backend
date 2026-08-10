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
        if ($request->user()->role !== 'superadmin') abort(403);
        $filters = $request->only(['search', 'date_from', 'date_to']);
        return $this->userService->listUser($request->input('per_page', 15), $filters);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'superadmin') abort(403);
        $payload = $request->all();
        if (isset($payload['password'])) {
            $payload['password'] = \Illuminate\Support\Facades\Hash::make($payload['password']);
        }
        return $this->userService->createUser($payload);
    }

    public function show(Request $request, string $uuid)
    {
        if ($request->user()->role !== 'superadmin') abort(403);
        return $this->userService->getUser($uuid);
    }

    public function update(Request $request, string $uuid)
    {
        if ($request->user()->role !== 'superadmin') abort(403);
        $payload = $request->all();
        if (isset($payload['password'])) {
            $payload['password'] = \Illuminate\Support\Facades\Hash::make($payload['password']);
        }
        return $this->userService->updateUser($uuid, $payload);
    }

    public function destroy(Request $request, string $uuid)
    {
        if ($request->user()->role !== 'superadmin') abort(403);
        $this->userService->deleteUser($uuid);

        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    public function restore(Request $request, string $uuid)
    {
        if ($request->user()->role !== 'superadmin') abort(403);
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

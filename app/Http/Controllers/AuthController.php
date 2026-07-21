<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        return $this->userService->loginUser($request);
    }

    public function logout(Request $request)
    {
        return $this->userService->logoutUser($request->user());
    }

    public function generateNickname()
    {
        $prefixes = [
            'Anonymous',
            'Mystic',
            'Cosmic',
            'Shadow',
            'Silent',
            'Velvet',
            'Crystal',
            'Phantom',
            'Echo',
            'Phoenix',
            'Lunar',
            'Solar',
            'Starlight',
            'Thunder',
            'Frost',
            'Neon',
            'Golden',
            'Silver',
            'Amber',
            'Crimson',
            'Cobalt',
            'Emerald',
            'Sapphire',
            'Obsidian',
            'Marble',
            'Breeze',
            'Horizon',
            'Vortex',
            'Siren',
            'Zenith'
        ];

        do {
            // Pick a random prefix from the array
            $randomPrefix = $prefixes[array_rand($prefixes)];

            // Combine prefix + random number
            $nickname = $randomPrefix . rand(1000, 9999);
        } while (User::where('nickname', $nickname)->exists());

        return response()->json(['nickname' => $nickname]);
    }
}

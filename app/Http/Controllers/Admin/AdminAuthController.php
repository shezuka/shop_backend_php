<?php

namespace App\Http\Controllers\Admin;

use App\Facades\CustomHash;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\ValidateAccessTokenRequest;
use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login(AdminLoginRequest $loginRequest)
    {
        $credentials = $loginRequest->validated();
        $user = User::where('username', $credentials['username'])->first();
        if (!isset($user) || !CustomHash::check($credentials['password'], $user->encrypted_password) || !$user->is_full_admin) {
            return response()->json(null, 401);
        }

        $token = new AccessToken([
            'user_id' => $user->id,
            'key' => bin2hex(random_bytes(32)),
        ]);
        $token->saveOrFail();
        return response()->json([
            'key' => $token->key,
        ]);
    }
}

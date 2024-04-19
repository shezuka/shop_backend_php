<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateAccessTokenRequest;
use App\Models\AccessToken;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function validateAccessToken(ValidateAccessTokenRequest $request)
    {
        $data = $request->validated();
        $token = AccessToken::where('key', $data['token'])->first();
        if (!isset($token)) return response()->json(null, 401);
        return response()->json(null);
    }
}

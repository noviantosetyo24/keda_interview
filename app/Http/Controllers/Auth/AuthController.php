<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return $this->resErrorJson([], $validator->messages(), 401);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->resErrorJson([], 'Credentials are invalid', 401);
            }
        } catch (JWTException $e) {
            return $this->resErrorJson([], 'Failed to generate token', 500);
        }

        return $this->resSuccessJson([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            if (!auth()->check()) {
                return $this->resErrorJson([], 'Token is invalid', 401);
            }

            JWTAuth::parseToken()->invalidate();

            return $this->resSuccessJson([], 'User has been logged out');
        } catch (JWTException $e) {
            if ($e instanceof TokenBlacklistedException) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Token is invalid',
                    'result' => []
                ], 401);
            } else {
                return $this->resErrorJson([], 'Internal server error');
            }
        }
    }
}

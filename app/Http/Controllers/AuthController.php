<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login() {
        $credentials = request(['email', 'password']);

        //Token erzeugen
        //var_dump(auth()->attempt($credentials));
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    //beendet Gültigkeit vom JWT
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'Logout erfolgreich']);
    }

    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }
}

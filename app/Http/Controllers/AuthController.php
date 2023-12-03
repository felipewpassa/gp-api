<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    use HttpResponses;

    public function login(Request $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->response('Usuario logado com sucesso', 200, [
                'token' => $request->user()->createToken('teste')->plainTextToken
            ]);
        }

        return $this->error('Credenciais inválidas', 401);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Logout com sucesso', 200);
    }
}

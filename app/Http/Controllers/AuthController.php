<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use HttpResponses;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Dados invalidos', 422, $validator->errors());
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            if (Auth::check()) {
                $request->user()->tokens()->delete();
            }

            return $this->response('Usuario logado com sucesso', 200, [
                'token' => $request->user()->createToken('api')->plainTextToken
            ]);
        }

        return $this->error('Credenciais inválidas', 401);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Logout com sucesso', 200);
    }

    public function verify()
    {
        if (Auth::check()) {
            return $this->response('', 200);
        }
        return $this->error('', 403);

    }
}

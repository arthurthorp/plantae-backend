<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'surname' => 'required',
                'birth_date' => 'required',
                'phone' => 'required|unique:users,phone',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'email',
                'password' => 'required'
            ]);

            if($validateUser->fails() || (!isset($request->email) && !isset($request->phone))){
                return response()->json([
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(isset($request->email)){
                if(!Auth::attempt($request->only(['email', 'password']))){
                    return response()->json([
                        'message' => 'E-mail e senha não corresponde em nosso registro.',
                    ], 401);
                }

                $user = User::where('email', $request->email)->first();
            }else {
                if(!Auth::attempt($request->only(['phone', 'password']))){
                    return response()->json([
                        'message' => 'Telefone e senha não corresponde em nosso registro.',
                    ], 401);
                }

                $user = User::where('phone', $request->phone)->first();
            }


            return response()->json([
                'message' => 'Usuário logado com sucesso!',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

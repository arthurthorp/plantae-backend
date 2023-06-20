<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Plantation;
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
                'birth_date' => 'required',
                'phone' => 'required|unique:users,phone',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'message' => 'Campos inválidos',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $isOwner = !$request->query("plantation");
            $plantation = Plantation::find($request->query("plantation"));

            if(!$isOwner && !$plantation){
                return response()->json([
                    'message' => 'Essa plantação não existe em nossos registros'
                ], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_owner' => $isOwner,
                'password' => Hash::make($request->password)
            ]);


            if($isOwner){
                $token = $user->createToken("API TOKEN", ['owner'])->plainTextToken;
            }else {
                $user->plantations()->attach($request->query("plantation"));
                $token = $user->createToken("API TOKEN")->plainTextToken;
            }

            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'isOwner' => !$request->query("plantation"),
                'token' => $token
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
                    'message' => 'Campos inválidos',
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

            if($user->is_owner){
                $token = $user->createToken("API TOKEN", ['owner'])->plainTextToken;
            }else {
                $token = $user->createToken("API TOKEN")->plainTextToken;
            }

            return response()->json([
                'message' => 'Usuário logado com sucesso!',
                'isOwner' => $user->is_owner,
                'token' => $token
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

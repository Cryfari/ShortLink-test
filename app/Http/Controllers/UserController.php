<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request){
        $data = $request->validated();

        if (User::where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]
                ]
            ], 400));
        }
        $user = new User($data);
        $user->id = Str::uuid();
        $user->password = Hash::make($data['password'], [
            'rounds' => 10
        ]);
        $user->save();

        $respose = [
            'data' => [
                'username'=> $user->username,
            ]
        ];

        return response()->json($respose)->setStatusCode(201);
    }
    
    public function login(UserLoginRequest $request){
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "username or password is incorrect"
                ]
            ], 400));
        }

        $user->token = Str::random(60);
        $user->save();

        $respose = [
            'data' => [
                'username'=> $user->username,
                'token'=> $user->token
            ]
        ];

        return response()->json($respose)->setStatusCode(201);
    }

    public function get(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'data' => $user
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if (isset($data['username'])) {
            $user->username = $data['username'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password'], [
                'rounds' => 10
            ]);
        }

        $user->save();
        return response()->json([
            'data' => [
                'message' => 'user updated'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{

    /**
     * creating new user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request){
        $fields=$request->validated();
        $user = User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>Hash::make($fields['password']),
        ]);
        $token=$user->createToken('token-name', ["role-{$user->refresh()->role}"])->plainTextToken;
        return Response()->json(['token'=>$token],201);
    }

    /**
     * login in user
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request){
        $fields=$request->validated();
        $user = User::where('email',$fields['email'])->first();
        if(!Hash::check($fields['password'],$user->password)){
            return Response()->json("Forbidden",403);
        }

        $token=$user->createToken('token-name', ["role-{$user->role}"])->plainTextToken;
        return Response()->json(['token'=>$token],200);
    }

    /**
     * login out  user
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request){
        $user=Auth::user();
        $request->user()->currentAccessToken()->delete();
        Auth::guard('web')->logout();
        return Response()->json("Logged out",200);
    }
}

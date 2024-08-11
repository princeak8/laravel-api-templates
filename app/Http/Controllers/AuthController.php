<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Login;

use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Login $request){
        $credentials = $request->only('username', 'password');
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Wrong Username or Password'
            ], 402);
        }
        $user = new UserResource(Auth::user());
        return response()->json([
            'statusCode' => 200,
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'token_expires_in' => Auth::factory()->getTTL(), 
                'user' => $user
            ]
        ], 200);
    }
    
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewerLogin(ViewerLogin $request){
        $credentials = $request->only('username', 'password');
        if (! $token = Auth::guard('viewer')->attempt($credentials)) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Wrong Username or Password'
            ], 402);
        }
        $user = new ViewerResource(Auth::guard('viewer')->user());
        if(Auth::guard('viewer')->user()->viewing_right == 1) {
            return response()->json([
                'statusCode' => 200,
                'data' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'token_expires_in' => Auth::guard('viewer')->factory()->getTTL(), 
                    'viewer' => $user
                ]
            ], 200);
        }else{
            return response()->json([
                'statusCode' => 202,
                'message' => 'user not authorised to view',
                'data' => [
                    'viewer' => $user
                ]
            ], 202);
        }
    }
}

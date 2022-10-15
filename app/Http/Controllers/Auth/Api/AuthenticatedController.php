<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthenticatedController extends Controller
{
    /**
     * Handle an incoming Sanctum authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return string $plainTextToken
     */
    public function authenticate(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {
            $request->authenticate();
            $user = User::where('email', $request->email)->first();
            return $user->createToken($request->app_name)->plainTextToken;
        } catch (ValidationException $th) {
            return response()->json($th->getMessage(), 400);
        }
    }
}
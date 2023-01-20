<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginApiRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(LoginApiRequest $request)
    {
        $input = $request -> validated();
        if(!Auth::attempt($input))
        {
            return response()->json(['message' => 'Unauthorized!'], 401);
        }

        $tokenResult = Auth::user()->createToken('token')->plainTextToken;

        return response()->json(['data' => [
            'user' => new UserResource(Auth::user()),
            'token' => $tokenResult
        ]]);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()-> where('id', $request->token_id)->delete();
        return response()->json(['message' => 'you are successfully logout']);
    }

    public function check(Request $request)
    {
        return response([
            'message' => 'success',
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'phone' => $request->user()->phone
            ]
        ]);
    }
}

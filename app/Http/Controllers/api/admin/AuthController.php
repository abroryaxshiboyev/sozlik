<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginApiRequest;
use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
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
        
        $null_token = Auth::user()->tokens()->where('last_used_at', null)->get()->first();

        if ($null_token)
        {
            $null_token->delete();
        }

        $now = Carbon::now();

        $old_day = $now->subDays(1);
        
        $old_token = Auth::user()->tokens()->where('last_used_at', '<=', $old_day)->get()->first();

        if ($old_token)
        {
            $old_token->delete();
        }

        $tokenResult = $request->user()->createToken('token')->plainTextToken;

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
            'data'=>[
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'phone' => $request->user()->phone
                ]
            ]
        ]);
    }
    // public function createAdmin()
}

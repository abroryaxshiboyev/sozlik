<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginApiRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    public function createAdmin(StoreUserRequest $request){
        $request->validated();
        $user=auth()->user(); 
        if($user->id==1){
            $user=User::create([
                'name' =>$request->name,
                'phone' =>$request->phone,
                'password' =>Hash::make($request->password)
            ]);
            return response()->json([
                'message'=>'created successfully',
                'data'=>$user
            ]);
        }else{
            return response([
                'message'=>'you have no such right'
            ]);
        }
    }
    public function deleteAdmin($id){
        
        $user=auth()->user(); 
        if($user->id==1){
            $delete_user=User::find($id);
            if(isset($delete_user)){
                if($delete_user->id==1)
                    return response([
                        'message'=>'you have no such right'
                    ]);
                $delete_user->tokens()->delete();
                $delete_user->delete();    
                return response()->json([
                    'message'=>'deleted successfully',
                ]);
            }else{
                return response()->json([
                    'message'=>'id not found',
                ]);  
            }
        }else{
            return response([
                'message'=>'you have no such right'
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\loginRequest;
use App\Http\Requests\User\storeRequest;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(storeRequest $request)
    {
        try {
            $user = $this->findUserByEmail(email: $request->email);
            if (!isset($user['id'])) {
                User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'role' => 'user'
                ]);
                return response()->successJson([],['message'=>'User added'],200);
            }else{
                return response()->errorJson([],['message'=>'User already exists'],400);
            }
        } catch (\Throwable $th) {
            return response()->errorJson([],['message'=>"Something went wrong"],500);
        }
    }
    public function login(loginRequest $request)
    {
        try {
            $user = $this->findUserByEmail($request->email);
            if (isset($user['id'])) {
                if(Auth::attempt($request->only('email','password'))){
                    $session_token = $this->generateRandomString();
                    $session = UserSession::create([
                        'user_id'=>$user['id'],
                        'session_token'=>$session_token
                    ]);
                    return response()->successJson($session,[],200);
                }else{
                    return response()->errorJson([],['message'=>'Invalid credentials'],400);
                }
            }else{
                return response()->errorJson([],['message'=>'Please register first'],400);
            }
        } catch (\Throwable $th) {
            return response()->errorJson([],['message'=>"Something went wrong"],500);
        }
    }

    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function generateRandomString(int $length = 128)
    {
        return Str::random($length);
    }

    public function getUserBySession($session){
        return UserSession::where('session_token',$session)->with('user')->first();
    }
}

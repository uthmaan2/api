<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\logUserRequest;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterUser $request){
        try {
            $user = User::create($request->all());
            return response()->json([
                'status_code'=>200,
                'status_message'=> 'utilisateur enregistre',
                'data'=>$user
            ]);
        } catch (Exception $e) {
            
            return response()->json($e);

        }
    }
    public function login(logUserRequest $request){
        if(auth()->attempt($request->only(["email","password"]))) {
            $user= auth()->user();
            $token = $user->createToken('MA_CLE_SECRETE')->plainTextToken;

            return response()->json([
                'status_code'=>200,
                'status_message'=> 'utilisateur connecté',
                'user'=>$user,
                "token"=>$token

               ]);

        }else
         {
            return response()->json([
                'status_code'=>403,
                'status_message'=> 'informations non valide'
               ]);
   
        }
    }

}

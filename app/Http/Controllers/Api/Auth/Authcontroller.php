<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class Authcontroller extends Controller
{
    // register
    public function register(Request $request){
        //validation 
        // $request->validate([
        //     'name' => "required|string|max:255",
        //     'email' => "required|string|email|unique:users,email",
        //     'password' => "required|min:8",
        // ]);
     #alternative method (to customize the validation)
       $validator=Validator::make($request->all(),[
         'name' => "required|string|max:255",
         'email' => "required|string|email|unique:users,email",
         'password' => "required|min:8", 
       ]);

       if($validator->fails()){
         return response()->json([
           "success" => false,
           "errors"  => $validator->errors(),
         ], 422);
       }

       $hashpass= Hash::make($request->password);

       //create
      $user= User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => $hashpass,
        'role'     => $request->role  ?? 'user',
       ]);

       $token = $user -> createToken('apiToken')->plainTextToken;

       //response
       return response()->json([
        'success' => true,
        'message' => "user created successufly",
        'token' => $token,
       ],201);
       
       }

    public function login (loginRequest $request){
      $request->validated();

        //check
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false,
                'message' =>"user invalid",
            ],403);

        }
            $token = $user -> createToken('apiToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'token'   => $token,
                'user'    => new UserResource($user),
            ],200);

        
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success'  => true,
            'message'  => "user loged out",
        ],200);
    }

    public function userDelete($id){
        $user = User::find();
        if(!$user){
          return response()->json([
            'success'  => false,
            'message'  => "user not found",
          ],404);

          $user->delete();

          return response()->json([
            'success'  => true,
            'message'  => "user deleted",
          ],200);
        }
    }

    public function allUsers(){
      $users = User::get();
      
      return response()->json([
            'success'  => true,
            'users'  => UserResource::collection($users),
      ],200);
    }


  
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request){ 

        $validator = Validator::make($request->all(), [ 
            'user_name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

    else{
    
    if(Auth::attempt(['email' => request('email'), 'password' => request('password'),'user_name' => request('user_name'),])){ 
        $user = Auth::user(); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        return response()->json([
            'success' => true,
            'token' => $success,
            'message' => 'Login successfully'
            ], $this-> successStatus);
    } 
    else {
       
        return response()->json([
          'success' => false,
          'message' => 'Invalid email or password or username',
      ], 401);
      }

}
    }

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required', 
            'last_name' => 'required', 
            'user_name' => 'required|unique:users', 
            'email' => 'required|email|unique:users', 
            'password' => 'required', 
            
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
              ], 401);           
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['user_name'] =  $user->user_name;
        return response()->json([
        'success' => true,
        'token' => $success
    ], $this-> successStatus);
    }



    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 


    public function logout(Request $res)
    {
      if (Auth::user()) {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
          'success' => true,
          'message' => 'Logout successfully'
      ]);
    }
      else {
        return response()->json([
          'success' => false,
          'message' => 'Unable to Logout'
        ]);
      }
     }
}

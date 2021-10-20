<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{    
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'nickname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10',
            'location' => 'required',
            'password'  => 'required|min:3|confirmed',
        ]);        
        
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }        
        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->nickname = $request->nickname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->location = $request->location;
        $user->save();        
        return response()->json(['status' => 'success'], 200);
    }   
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');   
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }        
        
        return response()->json(['error' => 'login_error'], 401);
    }    
    
    public function logout()
    {
        $this->guard()->logout();        
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }    
    
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }    
    
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }        
        return response()->json(['error' => 'refresh_token_error'], 401);
    }    
    
    private function guard()
    {
        return Auth::guard();
    }
}
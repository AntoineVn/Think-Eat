<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
/* use Illuminate\Support\Facades\Cookie;*/
use Cookie, Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->json()->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'nickname' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string',
                'location' => 'required|string|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'firstname' => $request->json()->get('firstname'),
                'lastname' => $request->json()->get('lastname'),
                'nickname' => $request->json()->get('nickname'),
                'email' => $request->json()->get('email'),
                'phone' => $request->json()->get('phone'),
                'location' => $request->json()->get('location'),
                'password' => Hash::make($request->json()->get('password')),
            ]);

            $name = $request->json()->get('lastname');
            $email = $request->json()->get('email');
            $password = $request->json()->get('password');
            $token = JWTAuth::fromUser($user);

            $verification_code = Str::random(30); //Generate verification code
            DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);

            $subject = "Please verify your email address.";
            Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
                function($mail) use ($email, $name, $subject){
                    $mail->from('think.eat@gmail.com','ThinkEatCorp');
                    $mail->to($email, $name);
                    $mail->subject($subject);
                });

            return response()->json(compact('user','token'), 201);
    }


    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();

        if(!is_null($check)){
            $user = User::find($check->user_id);

            if($user->email_verified_at == 1){
                return response()->json([
                    'success'=> true,
                    'message'=> 'Account already verified..'
                ]);
            }

            //$user->update(['email_verified_at' => 1]);
            $user->email_verified_at = date("Y-m-d H:i:s");
            $user->save();
            DB::table('user_verifications')->where('token',$verification_code)->delete();

            return response()->json([
                'success'=> true,
                'message'=> 'You have successfully verified your email address.'
            ]);
        }

        return response()->json(['success'=> false, 'error'=> "Verification code is invalid."]);
    }
    
    public function login(Request $request){
        $credentials = $request->json()->all();

        try {
            if(! $token = JWTAuth::attempt($credentials)){
                    return response()->json(['error'=>'invalid Credentials'], 400);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'could_not_create_token'], 500);
        }

       /*  Cookie::queue('token_test', 'test', 60);
        $test = Cookie::get('token_test');
        var_dump($test); */
        $cookie = cookie('token', $token, 60);
        return response()->json(compact('token'))->cookie($cookie)->header('Authorization', $token);
    }

    public function logout(Request $request){
        JWTAuth::invalidate(JWTAuth::getToken());
        Cookie::forget('token');
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function getAuthenticatedUser(){
        try{
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['user_not_found'], 400);
            }
        }catch (TokenExpiredException $e){
            return response()->json(['token_expired'], $e->getCode());
        }catch (TokenInvalidException $e){
            return response()->json(['token_invalid'], $e->getCode());
        }catch (JWTException $e){
            return response()->json(['token_absent'], $e->getCode());
        }

        return response()->json(compact('user'));
    }

    public function index()
    {
        $users = DB::table('users')
                    ->get();
        return response()->json(compact('users'), 201);
    }

    public function update(Request $request)
    {
        $id = $request->json()->get('id');
        $user = User::find($id);
        $validator = Validator::make($request->json()->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'nickname' => 'required|string|max:255|unique:users'.($id ? ",id,$id" : ''),
            'email' => 'required|string|email|max:255|unique:users'.($id ? ",id,$id" : ''),
            'phone' => 'required|string',
            'location' => 'required|string|max:255',
            'password' => 'string|min:6|confirmed',
        ]);

          if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        };

        if (!$password = $request->json()->get('password')) {
            $user->firstname = $request->json()->get('firstname');
            $user->lastname = $request->json()->get('lastname');
            $user-> nickname = $request->json()->get('nickname');
            $user-> email = $request->json()->get('email');
            $user-> phone = $request->json()->get('phone');
            $user->location = $request->json()->get('location');
            $user->save();
        }
        else {
            $user->firstname = $request->json()->get('firstname');
            $user->lastname = $request->json()->get('lastname');
            $user-> nickname = $request->json()->get('nickname');
            $user-> email = $request->json()->get('email');
            $user-> phone = $request->json()->get('phone');
            $user->location = $request->json()->get('location');
            $user->password = Hash::make($password);
            $user->save();
        }


        return response()->json(compact('user'), 201);
    }

    public function delete(Request $request)
    {
        $id = $request->json()->get('id');
        $user = User::find($id);
        
        $aliment = DB::table('kitchen')
        ->where('user_id', '=', $id)
        ->delete();

        $user->delete();
        JWTAuth::invalidate(JWTAuth::getToken());
        Cookie::forget('token');

        return response()->json(['status' => 'success'], 200);
    }
}
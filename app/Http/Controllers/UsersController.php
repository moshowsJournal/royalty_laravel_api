<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
    //
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'full_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:6'
        ]);
        if($validator->fails()){
            $response = array(
                'errors' => $validator->errors(),
                'code' => 400
            );
            return response()->json(compact('response'));
        }
        if($image_link = $this->uploadImageFile($request,'avatars/')){
            $request['profile_photo'] = $image_link;
        }
        $request['api_token'] = $token = Str::random(60);
        if(!$user = User::create($request->all())){
            $response = array(
                'message' => 'Opps! Account not created',
                'code' => 500
            );
            return response()->json(compact('user'));
        }
        $data['full_name'] = $request['full_name'];
        $data['email_address'] = $request['email'];
        $data['verification_code'] = $user->verification_code = rand(100000, 999999);
        $data['subject'] = 'Verify Email Address';
        $data['notification_type'] = 'Verify Email';
        $this->sendEmail($data);
        $user->save();
        $response = array(
            'token' => $token,
            'code' => 200,
            'user'=>$user
        );
        return response()->json(compact('response'));

    }

    
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'errors' => $validator->errors(),
                'code' => 400
            );
            return response()->json(compact('response'));
        }
        $user = User::where('email','=',$request->email)->first();
        if($user && $user->verified === 0){
            $data['full_name'] = $user['full_name'];
            $data['email_address'] = $user['email'];
            $data['verification_code'] = $user->verification_code = rand(100000, 999999);
            $data['subject'] = 'Verify Email Address';
            $data['notification_type'] = 'Verify Email';
            $this->sendEmail($data);
            $user->save();
            $response = array(
                'code' => 400,
                'message' => 'Account has not been activated. Check email address for activation code'
            );
            return response()->json(compact('response'));
        }
        if(!Auth::attempt($request->all())){
            $response = array(
                'message' => 'Invalid login details',
                'code' => 401
            );
            return response()->json(compact('response'));
        }
        $user = User::find(Auth::user()->id);
        $user->api_token = $token = Str::random(60);
        $user->save(); 
        $response = array(
            'code' => 200,
            'user' => $user
        );
        return response()->json(compact('response'));
    }

    
    public function verify_user_email(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'verification_code' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'code' => 400,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        $user = User::where('email','=',$request->email)->where('verification_code','=',$request->verification_code)->first();
        if(!$user){
            $response = array(
                'code' => 404,
                'message' => 'Invalid verification code'
            );
            return response()->json(compact('response'));
        }
        //record found
        $user->verified =  true;
        if(!$user->save()){
            $response = array(
                'code' => 500,
                'message' => 'Error! Record could not be saved'
            );
            return response()->json(compact('response'));
        }
        $response = array(
            'code'=>200,
            'message' => 'Success! Email verified!',
            'user' => $user
        );
        return response()->json(compact('response'));
    }



    
    public function forgot_password_email(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required'
        ]);            
        if($validator->fails()){
            $response = array(
                'code' => 400,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        $user = User::where('email','=',$request->email)->first();
        if(!$user){
            $response = array(
                'code' => '404',
                'message' => 'Account not found.'
            );
            return response()->json(compact('response'));
        }
        //send email
        $data['full_name'] = $user->full_name;
        $data['email_address'] = $user->email;
        $data['verification_code'] = $user->verification_code = rand(100000, 999999);
        $data['subject'] = 'Password Reset';
        $this->sendEmail($data);
        $user->save();
        $response = array(
            'code' => 200,
            'user'=>$user
        );
        return response()->json(compact('response'));
    }

    
    public function verify_forgot_password_pin(Request $request){
        $validator = Validator::make($request->all(),[
            'pin' => 'required',
            'email' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'code' => 400,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        $user = User::where('email','=',$request->email)->where('verification_code','=',$request->pin)->first();
        if(!$user){
            $response = array(
                'code' => 404,
                'message' => 'Invalid password reset PIN'
            );
            return response()->json(compact('response'));
        }
        $response = array(
            'code' => 200,
            'message' => 'Success! Valid PIN',
            'user' => $user
        );
        return response()->json(compact('response'));
    }

    public function reset_password(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'pin' => 'required',
            'password' => 'required|confirmed'
        ]);
        if($validator->fails()){
            $response = array(
                'code' => 400,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        $user = User::where('email','=',$request->email)->where('verification_code','=',$request->pin)->first();
        if(!$user){
            $response = array(
                'code' => 404,
                'message' => 'Invalid PIN and email address combination'
            ); 
            return response()->json(compact('response'));
        }
        if(!$user->fill($request->all())->save()){
            $response = array(
                'code' => 500,
                'message' => 'Error! Password could not be reset'
            );
            return response()->json(compact('response'));
        }
        $response = array(
            'code' => 200,
            'message' => 'Success! Record saved.'
        );
        return response()->json(compact('response'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Personalchat;
use App\GroupMember;
use App\ChurchGroup;
use App\Groupchat;
use App\FriendList;
use App\Event;
class UsersController extends Controller
{
    //
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'full_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
        if($request->password !== $request->confirm_password){
            $response = array(
                'code' => 400,
                'message' => 'Password do not match'
            );
            return response()->json(compact('response'),400);
        }
        if($validator->fails()){
            $response = array(
                'errors' => $validator->errors(),
                'code' => 400
            );
            return response()->json(compact('response'));
        }
        if($image_link = $this->uploadFile($request,'avatars/')){
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
        if($user && $user->verified === 0 && $user->type !== 'Admin'){
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
            return response()->json(compact('response'),401);
        }
        $user = User::find(Auth::user()->id);
        $user->is_online = 1;
        $user->api_token = $token = Str::random(60);
        $user->save();
        $response = array(
            'code' => 200,
            'user' => $user
        );
        return response()->json(compact('response'),200);
    }

    public function logout(){
        $user = User::find(Auth::user()->id);
        $user->is_online = 0;
        $user->save(); 
        $response = array(
            'code' => 200,
            'message' => 'Sucess! User has been logged out'
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

    public function get_personal_chats(Request $request){
        $validator = Validator::make($request->all(),[
            'friend_id' => 'required'  
        ]);
        if($validator->fails()){
            $response = array(
                'errors' => $validator->errors(),
                'code' => 401
            );
            return response()->json(compact('response'));
        }
        $messages = Personalchat::where(function($query) use($request){
            $query->where('sender_id','=',Auth::user()->id)->where('receiver_id','=',$request->friend_id);
        })->orWhere(function($query) use($request){
            $query->where('receiver_id','=',Auth::user()->id)->where('sender_id','=',$request->friend_id);
        })->with('receiver','sender')->get();
        $response = array(
            'code' => 200,
            'messages' => $messages
        );
        return response()->json(compact('response'));
    }

    

    public function save_personal_chats(Request $request){
        $validator = Validator::make($request->all(),[
            'receiver_id' => 'required',
            'message' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'code' => 401,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        
        $friendList = FriendList::where(function($query) use($request){
            $query->where('friend_id','=',$request->receiver_id)->where('friend_type','=','member')->where('user_id','=',Auth::user()->id);
        })->orWhere(function($query) use($request){
            $query->where('friend_id','=',Auth::user()->id)->where('friend_type','=','member')->where('user_id','=',$request->receiver_id);
        })->first();
        if($friendList === null){
            $friend['friend_id'] = $request->receiver_id;
            $friend['friend_type'] = 'member';
            $friend['user_id'] = Auth::user()->id;
            FriendList::create($friend);
        }
        $receiverExists = User::where('id','=',$request->receiver_id)->first();
        if($receiverExists === null){
            $response = array(
                'code' => 404,
                'message' => 'Receiver not found'
            );
            return response()->json(compact('response'));
        }
        $request['sender_id'] = Auth::user()->id;
        if(!Personalchat::create($request->all())){
            $response = array(
                'code' => 500,
                'message' => 'Opps! Message was not saved'
            );
            return response()->json(compact('response'));
        }
        $response = array(
            'code' => 200,
            'message' => 'Success! Message has been saved.'
        );
        return response()->json(compact('response'));
    }

    public function get_avaliable_members_and_groups(){
        $users = User::where('type','=','User')->where('id','!=',Auth::user()->id)->get();
        $group_member = GroupMember::where('member_id','=','all')->orWhere('member_id','=',Auth::user()->id)->with('church_group')->get();
        $response = array(
            'users' => $users,
            'groups' => $group_member
        );
        return response()->json(compact('response'));
    }

    public function get_group_chats(Request $request){
        $validator = Validator::make($request->all(),[
            'group_id' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'code' => 401,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        $chats = Groupchat::where('group_id','=',$request->group_id)->with('sender')->get();
        $response = array(
            'code' => 200,
            'chats' => $chats
        );
        return response()->json(compact('response'));
    }

    public function get_chat_list(Request $request){
        //c
        $counter = 0;
        $chat_list = FriendList::where(function($query){
            $query->where('friend_type','=','member')->where('user_id','=',Auth::user()->id); // this ensures I won't pick up rows where group id equals user_id
        })->orWhere(function($query) use($request){
            $query->where('friend_id','=',Auth::user()->id)->where('friend_type','=','member');
        })->orWhere(function($query){
            $query->where('user_id','=',Auth::user()->id)->where('friend_type','=','group');
        })->get()->map(function($chat) use($counter){
            //get last conversation
            $counter++;
            if($chat->friend_type === 'group'){
                $last_conversation = Groupchat::where('id','=',$chat->friend_id)->with('sender','church_group')->get()->sortByDesc('id')->take(1);
            }else{
                $last_conversation = Personalchat::where([
                    'sender_id' => $chat->friend_id,
                    'receiver_id' => Auth::user()->id
                ])->orWhere([
                    'sender_id' => Auth::user()->id,
                    'receiver_id' => $chat->friend_id
                ])->with('sender','receiver')->get()->sortByDesc('id')->take(1);
            }
            return $last_conversation;
        })->flatten();
        return response()->json(compact('chat_list'));
    }

    public function add_group_members(Request $request){
        $validator = Validator::make($request->all(),[
            'member_id' => 'required',
            'group_id' => 'required'
        ]);

        if($validator->fails()){
            $response = array(
                'code' => 401,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }

        $checkIfGroupExists = ChurchGroup::where('id','=',$request->group_id)->exists();
        if(!$checkIfGroupExists){
            $response = array(
                'code' => 404,
                'message' => 'Opps! Group not found'
            );
            return response()->json(compact('response'));
        } 
        $user = User::find($request->member_id);
        if($user === null){
            $response = array(
                'code' => 404,
                'message' => 'Opps! User record not found.'
            );
            return response()->json(compact('response'));
        }
        /***
         * User will be in group if group is for all members i.e member_id is for all 
         */
        $userIsInGroup = GroupMember::where(function($query) use($request){
            $query->where('group_id','=',$request->group_id)->where('member_id','=',$request->member_id);
        })->orWhere(function($query) use($request){
            $query->where('group_id','=',$request->group_id)->where('member_id','=','all');
        })->first();
    
        if($userIsInGroup !== null){
            $response = array(
                'code' => 401,
                'message' => 'Opps! User is already in the group'
            );
            return response()->json(compact('response'));
        }

        if(!GroupMember::create($request->all())){
            $response = array(
                'code' => 500,
                'messaege' => 'Error! Member not added to group' 
            );
        }
        $response = array(
            'code' => 200,
            'message' => 'Success! Member has been added to group'
        );
        return response()->json(compact('response'));

    }

    public function create_church_group(Request $request){
        $validator = Validator::make($request->all(),[
            'group_name' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'errors' => $validator->errors(),
                'code' => 401
            );
            return response()->json(compact('response'));
        }
        if(!$group = ChurchGroup::create($request->all())){
            $response = array(
                'code' => 500,
                'messaege' => 'Error! Group not created' 
            );
        }
        $response = array(
            'code' => 200,
            'message' => 'Success! Group has been created',
            'group' => $group
        );
        return response()->json(compact('response'));
    }

    public function save_group_chat(Request $request){
        $validator = Validator::make($request->all(),[
            'message' => 'required',
            'group_id' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'code' => 401,
                'errors' => $validator->errors()
            );
            return response()->json(compact('response'));
        }
        $friendList = FriendList::where([
            'friend_id' => $request->group_id,
            'friend_type' => 'group',
            'user_id' => Auth::user()->id
        ])->first();
        if($friendList === null){
            $friend['friend_id'] = $request->group_id;
            $friend['friend_type'] = 'group';
            $friend['user_id'] = Auth::user()->id;
            FriendList::create($friend);
        }
        $request['user_id'] = Auth::user()->id;
        if(!Groupchat::create($request->all())){
            $response = array(
                'code' => 500,
                'message' => 'Opps! Message was not saved. Please retry'
            );
            return response()->json(compact('response'));
        }
        $response = array(
            'code' => 200,
            'message' => 'Success! Message has been saved.'
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

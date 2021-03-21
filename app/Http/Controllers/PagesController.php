<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Auth;
    use App\User;

    class PagesController extends Controller{
        public function login(){
            if(request()->isMethod('post')){
                $validator = Validator::make(request()->all(),[
                    'email' => 'required|email',
                    'password' => 'required'
                ]);
                if($validator->fails()){
                    return redirect()->withErrors($validator);
                }
                $credentials = request()->only('email', 'password');
                if(Auth::attempt($credentials)){
                    return redirect('/add_events');
                }
                return back()->with('error','Error! Invalid login details');

            }
            return view('pages.login',compact('edit_event'));
        }

        public function signup(Request $request){
            $validator = Validator::make($request->all(),[
                'email' => 'required|unique:users',
                'password' => 'required'
            ]);
            if($validator->fails()){
                return back()->withErrors($validator);
            }
            $request['type'] = 'Admin';
            User::create($request->all());
            return back()->with('success','Success! Record has been saved');
        }
    }
?>
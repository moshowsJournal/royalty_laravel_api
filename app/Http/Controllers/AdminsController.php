<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use App\User;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use App\Event;
    use App\Job;

    class AdminsController extends Controller {

        public function __construct(){
            $this->middleware('auth');
        }

        public function get_events(){
            $events = Event::all();
            $response = array(
                'code' => 200,
                'message' => 'Record found',
                'data' => $events
            );
            return response()->json(compact('response'),200);
        }
    
        public function add_events(Request $request,$event_id = null){
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(),[
                    'event_photo' => 'mimes:jpeg,jpg,bmp,png',
                    'title'=>'required|max:255',
                    'description' => 'required'
                ]);
                if($validator->fails()){
                    return back()->with('error',"Fields marked with '*' are required and must be formatted correctly");
                }
                if($file_link = $this->uploadFile($request,'events/',$field_name = 'event_photo',$folder = 'public/events/')){
                    $request['image'] = $file_link;
                }
                if($event_id){
                    $event = Event::find(base64_decode($event_id));
                    if(!$event->fill($request->all())->update()){
                        return back()->with('error','Opps! Something went wrong. Please retry');
                    }
                    return redirect('/add_events')->with('success','Success! Record has been saved');
                }
                if(!Event::create($request->all())){
                    return back()->with('error','Opps Something went wrong. Please retry');
                }
                return back()->with('success','Success! Record has been saved');
            }
            $edit_event = null;
            if($event_id){
                $edit_event = Event::find(base64_decode($event_id));
            }
            $events = Event::all();
            return view('users.add_events',compact('events','edit_event'));
        }

        public function logout(){
            Auth::logout();
            return redirect('/login');
        }
        public function jobs(Request $request,$job_id = null){
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(),[
                    'job_photo' => 'mimes:jpeg,jpg,bmp,png',
                    'title'=>'required|max:255',
                    'description' => 'required',
                    'apply_link' => 'required|max:100'
                ]);
                if($validator->fails()){
                    return back()->withErrors($validator);
                }
                if($file_link = $this->uploadFile($request,'jobs/',$field_name = 'job_photo',$folder = 'public/jobs/')){
                    $request['image'] = $file_link;
                }
                if($job_id){
                    $job = Job::find(base64_decode($job_id));
                    if(!$job->fill($request->all())->update()){
                        return back()->with('error','Opps! Something went wrong. Please retry');
                    }
                    return redirect('/jobs')->with('success','Success! Record has been saved');
                }
                if(!Job::create($request->all())){
                    return back()->with('error','Opps Something went wrong. Please retry');
                }
                return back()->with('success','Success! Record has been saved');
            }
            $edit_job = null;
            if($job_id){
                $edit_job = Event::find(base64_decode($job_id));
            }
            $jobs = Job::all();
            return view('users.jobs',compact('jobs','edit_job'));
        }
    }
?>
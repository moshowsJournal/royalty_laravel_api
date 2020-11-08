<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use App\Mail\send_mail;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function sendEmail($data){
        Mail::to($data['email_address'])->send(new send_mail($data));
        if (Mail::failures()){
           return false; 
        }else{
            return true;
        }
    }

    public function uploadFile($request,$path,$field_name = 'profile_image',$folder = 'public/avatars/'){
        if($request->hasFile($field_name)){
            //get image file.
            $image = $request->$field_name;
            //dd($image);
            //get just extension.
            $ext = $image->getClientOriginalExtension();
            //make a unique name
            $filename = uniqid().'.'.$ext;
            //upload the image
            $image->storeAs($folder,$filename);
            return env('STORAGE_PATH').$path.$filename;   
        }
        return false;
    }

}

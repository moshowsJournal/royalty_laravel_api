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

    public function uploadImageFile($request,$path){
        // if($request->hasFile('image_file')){
        //     //get image file.
        //     $image = $request->image_file;
        //     //dd($image);
        //     //get just extension.
        //     $ext = $image->getClientOriginalExtension();
        //     //make a unique name
        //     $filename = uniqid().'.'.$ext;
        //     //upload the image
        //     $public = 'public/'.$path;
        //     $image->storeAs($public,$filename);
        //     return env('globalImagePath').$path.$filename;  
        // }
        if($request->hasFile('profile_image')){
            //get image file.
            $image = $request->profile_image;
            //dd($image);
            //get just extension.
            $ext = $image->getClientOriginalExtension();
            //make a unique name
            $filename = uniqid().'.'.$ext;
            //upload the image
            $image->storeAs('public/avatars/',$filename);
            return env('global_link').$path.$filename;   
        }
        return false;
    }

}

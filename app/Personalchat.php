<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personalchat extends Model
{
    //
    protected $table = 'personal_chats';
    protected $fillable = ['sender_id','receiver_id','message'];

    public function receiver(){
        return $this->belongsTO('App\User','receiver_id','id');
    }
    public function sender(){
        return $this->belongsTo('App\User','sender_id','id');
    }
}

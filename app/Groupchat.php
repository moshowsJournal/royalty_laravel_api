<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupchat extends Model
{
    //
    protected $fillable = ['user_id','message'];
    protected $table = 'group_chats';

    public function sender(){
        return $this->belongsTo('App\User','user_id','id');
    }

}

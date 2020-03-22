<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendList extends Model
{
    //
    protected $fillable = ['friend_id','friend_type','user_id'];
}

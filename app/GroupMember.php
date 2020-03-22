<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    //
    protected $fillable = ['member_id','group_id'];

    public function church_group(){
        return $this->belongsTo('App\ChurchGroup','group_id','id');
    }
}

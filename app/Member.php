<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['id_user','age','name'];
    protected $visible = ['id','id_user','age','name'];
    
    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }
}

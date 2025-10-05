<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['id_user','answer','score'];
    protected $visible = ['id','id_user','answer','score','created_at','user'];

    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }
}

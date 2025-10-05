<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    protected $table = 'user_score';
    protected $fillable = [];
    protected $visible = ['id','total','user'];

    public function user()
    {
        return $this->belongsTo('App\User','id');
    }
}

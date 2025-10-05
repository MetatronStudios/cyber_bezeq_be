<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $fillable = ['id_user','id_riddle','answer','is_correct'];

    // protected $visible = ['id','id_user','id_riddle','answer','is_correct','created_at','user','total', 'riddle'];

    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }

    public function riddle()
    {
        return $this->belongsTo('App\Riddle','id_riddle');
    }

}

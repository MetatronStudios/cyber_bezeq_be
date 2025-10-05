<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinalistScore extends Model
{
    protected $table = 'finalist_score';
    protected $fillable = [];
   //  protected $visible = ['id','id_user','total','answer', 'score','created_at','user'];

    public function user()
    {
        return $this->belongsTo('App\User','id_user');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Riddle extends Model
{
    protected $fillable = ['id_groupedRiddles','age','title','text','explain','hint','type','url','youtube','score'];
    protected $visible = ['id','id_groupedRiddles','age','title','text','explain','hint','type','url','youtube','answers','groupedRiddle','score'];

    public function answers()
    {
        return $this->hasMany('App\Answer', 'id_riddle');
    }

    public function groupedRiddle()
    {
        return $this->belongsTo('App\GroupedRiddles','id_groupedRiddles');
    }
}

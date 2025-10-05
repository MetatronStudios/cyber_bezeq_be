<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupedRiddles extends Model
{
    protected $fillable = ['name','start_at'];
    protected $visible = ['id','name','start_at','riddles'];
    
    public function riddles()
    {
        return $this->hasMany('App\Riddle', 'id_groupedRiddles');
    }
}

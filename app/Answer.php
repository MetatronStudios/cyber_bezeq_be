<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['id_riddle','text'];
    protected $visible = ['id','id_riddle','text'];
}

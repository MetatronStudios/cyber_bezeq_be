<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    protected $fillable = ['title','is_correct'];
    protected $visible = ['id','title','is_correct'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowedIp extends Model
{
    protected $primaryKey = 'ip';
    public $timestamps = false;

    protected $fillable = ['ip'];
}
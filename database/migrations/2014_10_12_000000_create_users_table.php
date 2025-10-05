<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->char('type', 1)->default('U');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_verified')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->index(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

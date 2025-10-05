<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->integer('age');
            $table->string('name');
            $table->timestamps();
            $table->index(['id','id_user','age','name']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('members');
    }
}

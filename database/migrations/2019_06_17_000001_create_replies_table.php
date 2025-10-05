<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned()->index();
            $table->integer('id_member')->unsigned()->index();
			$table->string('answer');
			$table->integer('score')->unsigned()->index();
            $table->timestamps();
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('replies');
    }
}

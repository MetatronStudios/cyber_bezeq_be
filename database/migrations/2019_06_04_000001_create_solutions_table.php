<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionsTable extends Migration
{
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned()->index();
            $table->integer('id_member')->unsigned()->index();
			$table->integer('id_riddle')->unsigned()->index();
			$table->string('answer');
			$table->integer('is_correct')->unsigned()->index();
            $table->timestamps();
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('solutions');
    }
}

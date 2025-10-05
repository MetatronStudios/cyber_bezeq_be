<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiddlesTable extends Migration
{
    public function up()
    {
        Schema::create('riddles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('age')->default(1)->index();
            $table->integer('id_groupedRiddles')->default(1)->index();
			$table->string('title')->nullable();
			$table->text('text')->nullable();
			$table->text('explain')->nullable();
			$table->string('hint')->nullable();
            $table->string('type')->nullable();
            $table->string('url',255)->nullable();
			$table->string('youtube')->nullable();
            $table->integer('score')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riddles');
    }
}

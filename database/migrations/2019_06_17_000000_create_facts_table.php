<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactsTable extends Migration
{
    public function up()
    {
        Schema::create('facts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
			$table->integer('is_correct');
            $table->timestamps();
            $table->index(['id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('facts');
    }
}

<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupedRiddlesTable extends Migration
{
    public function up()
    {
        Schema::create('grouped_riddles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('start_at')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grouped_riddles');
    }
}

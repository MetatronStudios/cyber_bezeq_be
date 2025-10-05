<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowedIpsTable extends Migration
{
    public function up()
    {
        Schema::create('allowed_ips', function (Blueprint $table) {
            $table->string('ip')->index();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('allowed_ips');
    }
}

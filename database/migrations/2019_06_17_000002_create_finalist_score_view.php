<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalistScoreView extends Migration
{
    public function up()
    {
        DB::statement("
        CREATE VIEW finalist_score AS
        SELECT * 
        FROM replies
        WHERE id IN (
            SELECT MAX(id) 
            FROM replies
            GROUP BY id_user
        )
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS finalist_score");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserScoreView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW user_score AS
            SELECT 
                id_user as id,
                COUNT(id) as total
            FROM solutions
            WHERE is_correct=1
            GROUP BY id_user
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS user_score");
    }
}

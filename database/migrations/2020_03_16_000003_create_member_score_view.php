<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberScoreView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW member_score AS
            SELECT 
                id_member as id,
                COUNT(id) as total
            FROM solutions
            WHERE is_correct=1
            GROUP BY id_member
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS member_score");
    }
}

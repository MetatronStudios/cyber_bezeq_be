<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastsSolutionsView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW lasts_solutions AS
            SELECT * 
            FROM solutions
            WHERE id IN (
                SELECT MAX(id) 
                FROM solutions
                GROUP BY id_user, id_riddle
            )
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS lasts_solutions");
    }
}

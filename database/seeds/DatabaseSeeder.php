<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (config('app.env') != 'local') {
            return 'nothing to seed';
        }

        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        //Cleaning tables
        DB::table('users')->truncate();
        DB::table('members')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('riddles')->truncate();
        DB::table('answers')->truncate();
        DB::table('solutions')->truncate();
        DB::table('facts')->truncate();
        DB::table('replies')->truncate();
        DB::table('configs')->truncate();

        //User
        $items = [
            ['id' => 1, 'type' => 'A', 'name' => 'Metatron', 'email' => 'baruch@metatron.co.il', 'password' => Hash::make('bSopW2!15'),'is_verified' => 1],
            ['id' => 2, 'type' => 'A', 'name' => 'חמיצר', 'email' => 'chamizer@inter.net.il', 'password' => Hash::make('ruth964491'),'is_verified' => 1],
        ];
        foreach($items as $item) {
            App\User::create($item)->save();
        }

        //Members
        // $items = [
        //     ['id' => 1, 'id_user' => 3, 'age' => 3, 'name' => 'ברוך'],
        //     ['id' => 2, 'id_user' => 3, 'age' => 3, 'name' => 'רותי'],
        //     ['id' => 3, 'id_user' => 3, 'age' => 2, 'name' => 'דניאל'],
        //     ['id' => 4, 'id_user' => 3, 'age' => 1, 'name' => 'יונתן']
        // ];
        // foreach($items as $item) {
        //     App\Member::create($item)->save();
        // }

        //GroupedRiddles
        $items = [
            ['id' => 1, 'name' => 'כללי', 'start_at' => self::getDate(0)],
        ];
        foreach($items as $item) {
            App\GroupedRiddles::create($item)->save();
        }

        //PasswordReset
        // $items = [
        //     ['email' => 'baruch@metatron.co.il', 'token' => '123'],
        // ];
        // foreach($items as $item) {
        //     App\PasswordReset::create($item)->save();
        // }



        //Riddle
      //   $items = [
      //       ['id' => 1, 'age'=> '1', 'id_groupedRiddles'=> '1', 'type' => 'Y','title' => 'title1','text' => 'text1','explain' => 'exp1','hint' => 'hint1','url' => 'url1','youtube' => 'youtube1'],
      //       ['id' => 2, 'age'=> '2', 'id_groupedRiddles'=> '1', 'type' => 'U','title' => 'title2','text' => 'text2','explain' => 'exp2','hint' => 'hint2','url' => 'url2','youtube' => 'youtube2'],
      //       ['id' => 3, 'age'=> '3', 'id_groupedRiddles'=> '1', 'type' => 'U','title' => 'title3','text' => 'text3','explain' => 'exp3','hint' => 'hint3','url' => 'url3','youtube' => 'youtube3']
      //   ];
      //   foreach($items as $item) {
      //       App\Riddle::create($item)->save();
      //   }

        //Answer
        // $items = [
        //     ['id' => 1,'id_riddle' => '1','text' => 'ans1'],
        //     ['id' => 2,'id_riddle' => '1','text' => '1'],
        //     ['id' => 3,'id_riddle' => '2','text' => 'ans2'],
        //     ['id' => 4,'id_riddle' => '2','text' => '2'],
        //     ['id' => 5,'id_riddle' => '3','text' => 'ans3'],
        //     ['id' => 6,'id_riddle' => '3','text' => '3'],
        // ];
        // foreach($items as $item) {
        //     App\Answer::create($item)->save();
        // }

        //Solution
        // $items = [
        //     ['id' => 1,'id_user' => 3, 'id_member' => 4, 'id_riddle' => 1,'answer' => '2','is_correct' => false],
        //     ['id' => 2,'id_user' => 3, 'id_member' => 4, 'id_riddle' => 1,'answer' => '1','is_correct' => true],
        //     ['id' => 3,'id_user' => 3, 'id_member' => 4, 'id_riddle' => 2,'answer' => 'ans2','is_correct' => true]
        // ];
        // foreach($items as $item) {
        //     App\Solution::create($item)->save();
        // }

        // //Fact
        // $items = [
        //     ['id' => 1,'title' => 'title1','is_correct' => 1],
        //     ['id' => 2,'title' => 'title2','is_correct' => 1],
        //     ['id' => 3,'title' => 'title3','is_correct' => 1],
        //     ['id' => 4,'title' => 'title4','is_correct' => 0],
        //     ['id' => 5,'title' => 'title5','is_correct' => 0],
        //     ['id' => 6,'title' => 'title6','is_correct' => 0]
        // ];
        // foreach($items as $item) {
        //     App\Fact::create($item)->save();
        // }

        // //Reply
        // $items = [
        //     ['id' => 1,'id_user' => 3, 'id_member' => 3, 'answer' => '4,5,6','score' => 3],
        //     ['id' => 2,'id_user' => 3, 'id_member' => 3,'answer' => '1,5,6','score' => 2],
        //     ['id' => 3,'id_user' => 3, 'id_member' => 3,'answer' => '1,2,6','score' => 1],
        //     ['id' => 4,'id_user' => 3, 'id_member' => 3,'answer' => '1,2,3','score' => 0],
        // ];
        // foreach($items as $item) {
        //     App\Reply::create($item)->save();
        // }

        //Config
        $items = [
            ['id' => 1,'text' => 'טקסט שרץ מרתון']
        ];
        foreach($items as $item) {
            App\Config::create($item)->save();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::reguard();
    }
    private function getDate( $day )
    {
        return date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+$day, date("Y")));
    }
}

<?php

namespace App\Repositories;

use App\ImportGroups;
use DateTime;
use DB;

class ImportGroupsRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new ImportGroups();
        $this->model->setConnection('mysql2');
    }

    public function list()
    {
        return $this->model->orderBy('start_at','asc')->paginate(1000);
    }

    public function addNew($id)
    {
        $oldGroupID = $id;
        $GroupData = DB::connection('mysql2')->table('grouped_riddles')->select('name','start_at')->where('id',$oldGroupID)->get()->map(function ($item, $key) {
          return (array) $item;
        })->first();
        $newGroupID = DB::connection('mysql')->table('grouped_riddles')->insertGetId($GroupData);
        $riddles = DB::connection('mysql2')->table('riddles')->select('id','age','id_groupedRiddles','title','text','explain','hint','type','url','youtube','created_at','updated_at')->where('id_groupedRiddles',$oldGroupID)->get()->map(function ($item, $key) {
          return (array) $item;
        })->all();
        foreach($riddles as $key=>$riddle)
        {
            $oldRiddleID = $riddle["id"];
            $riddles[$key]['id']="";
            $riddles[$key]["id_groupedRiddles"]=$newGroupID;
            $newRiddleID = DB::connection('mysql')->table('riddles')->insertGetId($riddles[$key]);
            $answers = DB::connection('mysql2')->table('answers')->select('id','id_riddle','text','created_at','updated_at')->where('id_riddle',$oldRiddleID)->get()->map(function ($item, $key) {
              return (array) $item;
            })->all();
            foreach($answers as $aKey=>$answer)
            {
                $oldAnswerID = $answer["id"];
                $answers[$aKey]["id"]="";
                $answers[$aKey]["id_riddle"]=$newRiddleID;
                $newAnswerID = DB::connection('mysql')->table('answers')->insertGetId($answers[$aKey]);
            }
            
        }
        return $newGroupID;
       
    }

    public function getAllWithAnswerNotLogged()
    {
      
        $GroupedRiddles = $this->model::with(['riddles' => function($query) {
            $query->select(['id','title','id_groupedRiddles','age'])->orderBy('age','asc');
        }])->where('start_at','<=',now()->setTime(0,0,0)->toDateTimeString())->get();
        
        $GroupedRiddles = $GroupedRiddles->map(function($Group){
            $Group =collect($Group);
            $Group["riddles"] =collect($Group["riddles"]);
            foreach($Group["riddles"] as $Key=>$Riddle)
            {   
                $Group["riddles"][$Key] =collect($Group["riddles"][$Key]);
                $Group["riddles"][$Key]->put("is_correct",0);
            }
            return $Group;
        });
        
        return $GroupedRiddles;
        
        //return $this->model::with('riddles:id,title,id_groupedRiddles,age')->where('start_at','<=',now()->setTime(0,0,0)->toDateTimeString())->get();
    }

    public static $SolutionOfUser;
    public static $totalCorrectForUser;

    public function getAllWithAnswer($id_user)
    {
        $GroupedRiddles = $this->model::with(['riddles' => function($query) {
            $query->select(['id','title','id_groupedRiddles','explain','age'])->orderBy('age','asc');
        }])->where('start_at','<=',now()->setTime(0,0,0)->toDateTimeString())->get();
       
        self::$SolutionOfUser = \App\Solution::where("id_user",$id_user)->where("is_correct",1)->get();
        $GroupedRiddles = $GroupedRiddles->map(function($Group){
            self::$totalCorrectForUser=0;
            $Group =collect($Group);
            $Group["riddles"] =collect($Group["riddles"]);
            $totalRiddles=0;
            foreach($Group["riddles"] as $Key=>$Riddle)
            {
                $totalRiddles++;
                $Group["riddles"][$Key] =collect($Group["riddles"][$Key]);
                $isCorrect = self::$SolutionOfUser->where("id_riddle",$Group["riddles"][$Key]["id"])->count();
                $Member = collect(self::$SolutionOfUser->where("id_riddle",$Group["riddles"][$Key]["id"])->flatten())->toArray();
                self::$totalCorrectForUser+=$isCorrect; // count how many correct answers for the user
                $Group["riddles"][$Key]->put("is_correct",$isCorrect); // did the user answer correct for this riddle?
                $Group["riddles"][$Key]->put("member",($isCorrect)?$Member[0]["id_member"]:""); // add member_id to the results
                $Group["riddles"][$Key]->put("answer",($isCorrect)?$Member[0]["answer"]:""); // if correct show the answer
                $Group["riddles"][$Key]->put("explain",($isCorrect)?$Group["riddles"][$Key]["explain"]:""); // if correct allow the explain to be showen

            }
            if (self::$totalCorrectForUser>=$totalRiddles)
                $Group->put("groupCorrect",1);
            else
                $Group->put("groupCorrect",0);
            return $Group;
            
        });
        

        return $GroupedRiddles;
       
    }

}
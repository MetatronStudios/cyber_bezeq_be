<?php

namespace App\Repositories;

use App\GroupedRiddles;
use DateTime;
use DB;

class GroupedRiddlesRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new GroupedRiddles();
    }

    public function getAllWithAnswerNotLogged()
    {

        $GroupedRiddles = $this->model::with(['riddles' => function($query) {
            $query->select(['id','title','text','hint','id_groupedRiddles','age'])->orderBy('id','asc');
        }])->where('start_at','<=',now()->toDateTimeString())->orderBy('id','asc')->get();
        $cleanedRiddles = collect();
        $GroupedRiddles = $GroupedRiddles->map(function($Group,$index) use ($cleanedRiddles) {
            $i=$index+1;
            $Group =collect($Group);
            $Group["n"]=$i;
            $Group["riddles"] =collect($Group["riddles"]);
            foreach($Group["riddles"] as $Key=>$Riddle)
            {
                $Group["riddles"][$Key] =collect($Group["riddles"][$Key]);
                $Group["riddles"][$Key]->put("is_correct",0);
                $Group["riddles"][$Key]->put("retries",0);
                $cleanedRiddles->push($Group["riddles"][$Key]);
            }

            return $Group;
        });
        $GroupedRiddles->put("cleanedRiddles",$cleanedRiddles);
        return $GroupedRiddles;

        //return $this->model::with('riddles:id,title,id_groupedRiddles,age')->where('start_at','<=',now()->setTime(0,0,0)->toDateTimeString())->get();
    }

    public static $SolutionOfUser;
    public static $totalCorrectForUser;

    public function getAllWithAnswer($id_user)
    {
        $GroupedRiddles = $this->model::with(['riddles' => function($query) {
            $query->select(['id','title','text','hint','id_groupedRiddles','explain','age'])->orderBy('id','asc');
        }])->where('start_at','<=',now()->toDateTimeString())->orderBy('id','asc')->get();

        self::$SolutionOfUser = \App\Solution::where("id_user",$id_user)->get();
        $i=0;
        $cleanedRiddles = collect();
        $GroupedRiddles = $GroupedRiddles->map(function($Group,$index) use ($cleanedRiddles){

            $i=$index+1;
            $Group =collect($Group);
            $Group["n"]=$i;
            self::$totalCorrectForUser=0;
            $Group =collect($Group);
            $Group["riddles"] =collect($Group["riddles"]);
            $totalRiddles=0;
            foreach($Group["riddles"] as $Key=>$Riddle)
            {
                $totalRiddles++;
                $Group["riddles"][$Key] =collect($Group["riddles"][$Key]);
                $isCorrect = self::$SolutionOfUser->where("id_riddle",$Group["riddles"][$Key]["id"])->where("is_correct",1)->count();
                if ($isCorrect>0)
                    $isCorrect=1;
                $retries = 0; // self::$SolutionOfUser->where("id_riddle",$Group["riddles"][$Key]["id"])->count();
                $Member = collect(self::$SolutionOfUser->where("id_riddle",$Group["riddles"][$Key]["id"])->flatten())->toArray();
                self::$totalCorrectForUser+=$isCorrect; // count how many correct answers for the user
                $Group["riddles"][$Key]->put("retries",$retries); // how many times the user tried to answer this riddle
                $Group["riddles"][$Key]->put("is_correct",$isCorrect); // did the user answer correct for this riddle?
                $Group["riddles"][$Key]->put("answer",($isCorrect)?$Member[0]["answer"]:""); // if correct show the answer
                $Group["riddles"][$Key]->put("explain",($isCorrect)?$Group["riddles"][$Key]["explain"]:""); // if correct allow the explain to be showen
                $cleanedRiddles->push($Group["riddles"][$Key]);
            }
            if (self::$totalCorrectForUser>=$totalRiddles)
                $Group->put("groupCorrect",1);
            else
                $Group->put("groupCorrect",0);
            return $Group;

        });

        $GroupedRiddles->put("cleanedRiddles",$cleanedRiddles);
        return $GroupedRiddles;

    }
    public function getAllPaginate($data)
    {
        $sort = $data && $data['sort'] ? $data['sort'] : 'id';
        $order = $data && $data['order'] ? $data['order'] : 'desc';
        return $this->model::orderBy($sort, $order)->paginate(1000);
    }
}

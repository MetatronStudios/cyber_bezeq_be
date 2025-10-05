<?php

namespace App\Repositories;

use App\Riddle;
use App\GroupedRiddles;
use DB;

class RiddleRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Riddle();
        $this->gmodel = new GroupedRiddles();
    }

    public function getAll()
    {
        return $this->model::select('id','title','age')->orderBy('id')->get();
    }

    public function getAllWithAnswers()
    {
        return $this->model::with('answers')->select('id','title','text','explain')->orderBy('id')->get();
    }

    public function getAllPaginate($data)
    {
        $sort = $data['sort'] ? $data['sort'] : 'id';
        $order = $data['order'] ? $data['order'] : 'desc';
        return $this->model::with(['groupedRiddle','answers'])->orderBy($sort, $order)->paginate(10000);
        //->orderBy($sort, $order)->paginate(10);
        //return $this->model::with(['answers','groupedRiddle'])->orderBy($sort, $order)->paginate(10);
    }

	public function getById($id)
    {
        return $this->model::with('answers')->find($id);
    }

    public function getAllWithAnswer($id_user)
    {
        $params = [
            'id_user' => $id_user
        ];
        return DB::select('select
                                r.id as id,
                                r.type as type,
                                r.title as title,
                                r.text as text,
                                r.explain as exp,
                                r.hint as hint,
                                r.url as url,
                                r.youtube as youtube,
                                s.is_correct as is_correct
                            FROM riddles r
                            LEFT JOIN lasts_solutions s on (s.id_riddle = r.id AND s.id_user=:id_user)
                            ORDER by r.id limit 15', $params);
    }

    public function getAllWithAnswerNotLogged()
    {
        return DB::select('select
                                r.id as id,
                                r.type as type,
                                r.title as title,
                                r.text as text,
                                \'\' as exp,
                                r.hint as hint,
                                r.url as url,
                                r.youtube as youtube,
                                null as is_correct
                            FROM riddles r
                            ORDER by r.id limit 15');
    }
}

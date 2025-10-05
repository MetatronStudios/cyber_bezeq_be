<?php

namespace App\Repositories;

use App\Solution;

class SolutionRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Solution();
    }

    public function getNumberofUserSolutionsOfRiddle($user_id, $riddle_id)
    {
        $query = $this->model::query();
        return $query->where('id_user', $user_id)->where('id_riddle', $riddle_id)->count();
    }

    public function getAllWiteScorePaginate($data)
    {
        $query = $this->model::query();
        if ($data && $data['id_riddle'] && $data['id_riddle'] != '') {
            $query->where('id_riddle', $data['id_riddle']);
        }
        if ($data && $data['from'] && $data['from'] != '') {
            $query->where('created_at', '>=', $data['from']);
        }
        if ($data && $data['to'] && $data['to'] != '') {
            $query->where('created_at', '<=', $data['to']);
        }
        if ($data && $data['is_correct'] && $data['is_correct'] == 1) {
            $query->where('is_correct', 1);
        }

        $sort = $data && $data['sort'] ? $data['sort'] : 'id';
        $order = $data && $data['order'] ? $data['order'] : 'desc';
        return $query->with(['user', 'riddle:id,score'])->orderBy($sort, $order)->paginate(10);
    }

    public function getallWithScore()
    {
        $query = $this->model::query();
        return $query->join('riddles', 'solutions.id_riddle', '=', 'riddles.id')
            ->join('users', 'solutions.id_user', '=', 'users.id')
            ->select('solutions.id as id',
                'solutions.id_user as id_user',
                'solutions.id_riddle as id_riddle',
                'solutions.answer as answer',
                'solutions.is_correct as is_correct',
                'solutions.created_at as created_at',

             'riddles.score as score', 'users.email as email')
            ->get();
        //$query->with(['user:email', 'riddle:id,score'])->get();
    }

    public function getAllPaginate($data)
    {
        $query = $this->model::query();
        if ($data && $data['id_riddle'] && $data['id_riddle'] != '') {
            $query->where('id_riddle', $data['id_riddle']);
        }
        if ($data && $data['from'] && $data['from'] != '') {
            $query->where('created_at', '>=', $data['from']);
        }
        if ($data && $data['to'] && $data['to'] != '') {
            $query->where('created_at', '<=', $data['to']);
        }
        if ($data && $data['is_correct'] && $data['is_correct'] == 1) {
            $query->where('is_correct', 1);
        }

        $sort = $data && $data['sort'] ? $data['sort'] : 'id';
        $order = $data && $data['order'] ? $data['order'] : 'desc';
        return $query->with('user')->orderBy($sort, $order)->paginate(10);
    }

    public function checkIfCorrectExists($id_riddle,$id_user){
        $query = $this->model::query();
        return $query->where('id_riddle',$id_riddle)->where('id_user',$id_user)->where('is_correct',1)->orderBy('id')->first();
    }
}

<?php

namespace App\Repositories;

use App\Answer;

class AnswerRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Answer();
    }
    
    public function deleteByRiddle($id_riddle)
    {
        return $this->model->where('id_riddle', $id_riddle)->delete();
    }
}
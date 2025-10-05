<?php

namespace App\Repositories;

use App\Reply;

class ReplyRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Reply();
    }
    
    public function getAllPaginate($data)
    {
        $query = $this->model::query();
        $sort = $data && $data['sort'] ? $data['sort'] : 'id';
        $order = $data && $data['order'] ? $data['order'] : 'desc';
        return $query->with(['user'])->orderBy($sort, $order)->paginate(10);
    }
}
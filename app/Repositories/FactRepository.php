<?php

namespace App\Repositories;

use App\Fact;

class FactRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Fact();
    }

    public function getAll()
    {
        return $this->model::select('id','title')->orderBy('id')->get();
    }
}

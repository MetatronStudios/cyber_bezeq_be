<?php

namespace App\Repositories;

use App\Member;

class MemberRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Member();
    }

    public function getByIdUser($id_user)
    {
      return parent::getBy(['id_user'=>$id_user]);
    }
}
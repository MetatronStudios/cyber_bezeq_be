<?php
namespace App\Services;

use App\Repositories\MemberRepository;

class MemberService extends ParentService
{
    public function __construct()
    {
        $this->repository = new MemberRepository();
    }

    public function getByIdUser($id_user)
    {
        return $this->repository->getByIdUser($id_user);
    }
}
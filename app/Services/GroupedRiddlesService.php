<?php
namespace App\Services;

use App\Repositories\GroupedRiddlesRepository;
use App\Exceptions\BusinessException;

class GroupedRiddlesService extends ParentService
{
    public function __construct()
    {
        $this->repository = new GroupedRiddlesRepository();
    }

    public function add($data)
    {
        $return = parent::add($data);
        return $return;
    }

    public function getPlayableGroups($id_user)
    {
        if ( $id_user == 0 ) {
            return $this->repository->getAllWithAnswerNotLogged();
        }
        else {
            return $this->repository->getAllWithAnswer($id_user);
        }
    }
}
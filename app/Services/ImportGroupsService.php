<?php
namespace App\Services;

use App\Repositories\ImportGroupsRepository;
use App\Exceptions\BusinessException;

class ImportGroupsService extends ParentService
{
    public function __construct()
    {
        $this->repository = new ImportGroupsRepository();
    }

    public function list()
    {
        $return = $this->repository->list();
        return $return;
    }

    public function addNew($id)
    {
        $return = $this->repository->addNew($id);
        return $return;
    }
}
<?php
namespace App\Services;

use App\Repositories\FactRepository;

class FactService extends ParentService
{
    public function __construct()
    {
        $this->repository = new FactRepository();
    }

    public function getAllPaginate($data)
    {
        $items = $this->repository->getAll();
        if ( count($items) == 0 ) {
            $this->populate();
        }
    	return $this->repository->getAllPaginate($data);
    }

    private function populate()
    {
        $total = config('app.total_facts', 70);
        for ($i = 1; $i <= $total; $i++) {
            $data = [   'title' => 'title',
                        'is_correct' => 0];
            $this->add($data);
        }
    }
}

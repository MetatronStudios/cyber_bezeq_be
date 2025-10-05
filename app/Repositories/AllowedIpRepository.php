<?php

namespace App\Repositories;

use App\AllowedIp;

class AllowedIpRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new AllowedIp();
    }

    public function getByIp( $ip )
    {
        return $this->model->where('ip', $ip)->first();
    }
}
<?php

namespace App\Repositories;

use App\Config;

class ConfigRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new Config();
    }
}
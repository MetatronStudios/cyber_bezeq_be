<?php
namespace App\Services;

use App\Repositories\ConfigRepository;

class ConfigService extends ParentService
{
    public function __construct()
    {
        $this->repository = new ConfigRepository();
    }
}
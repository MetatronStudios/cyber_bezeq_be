<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\AllowedIpRepository;

class AllowedIpTest extends TestCase
{
    public function test_getByIp()
    {
        $repository = new AllowedIpRepository();
        $result = $repository->getByIp( '127.0.0.1' );
        $this->assertEquals(false, $result);
    }

    public function test_getByFirst()
    {
        $repository = new AllowedIpRepository();
        $result = $repository->getByFirst( [['ip' => '127.0.0.1']] );
        $this->assertEquals(false, $result);
    }
}

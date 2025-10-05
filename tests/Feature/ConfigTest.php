<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ConfigService;

class ConfigTest extends TestCase
{
    use DatabaseTransactions;

    public function test_getAll()
    {
        $service = new ConfigService();
        $result = $service->getAll();
        $this->assertEquals(1, count($result));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\FactService;

class FactTest extends TestCase
{
    use DatabaseTransactions;

    public function test_getAllPaginate()
    {
        $service = new FactService();
        $data = [
            'sort' => 'id',
            'order' => 'desc'
        ];
        $result = $service->getAllPaginate($data);
        $this->assertEquals(6, count($result));
    }

    public function test_getAll()
    {
        $service = new FactService();
        $result = $service->getAll();
        $this->assertEquals(6, count($result));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\SolutionService;

class SolutionTest extends TestCase
{
    use DatabaseTransactions;
    
    public function test_getAllPaginate()
    {
        $service = new SolutionService();
        $data = [
            'id_riddle' => 1,
            'from' => '2017-12-01',
            'to' => '2021-12-01',
            'is_correct' => 1,
            'sort' => 'id',
            'order' => 'desc'
        ];
        $result = $service->getAllPaginate($data);
        $this->assertEquals(1, count($result));
    }
    
    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    Error, id not found.
     */
    public function test_getById_error()
    {
        $service = new SolutionService();
        $result = $service->getById(4000);
    }

    public function test_getById()
    {
        $service = new SolutionService();
        $result = $service->getById(1);
        $this->assertEquals(1, $result->id);
    }

    public function test_add()
    {
        $data = [
            'id_riddle' => '1',
            'answer' => '1'
        ]; 
        $service = new SolutionService();
        $result = $service->insertAnswer(2, $data);
        $this->assertEquals(true, $result->is_correct);
    }
}
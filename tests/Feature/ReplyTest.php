<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ReplyService;

class ReplyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_getAllPaginate()
    {
        $service = new ReplyService();
        $data = [
            'sort' => 'id',
            'order' => 'desc'
        ];
        $result = $service->getAllPaginate($data);
        $this->assertEquals(4, count($result));
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    Wrong format.
     */
    public function test_insertReplies_error()
    {
        $data = [
            'replies' => '1,2,3,4'
        ];
        $service = new ReplyService();
        $result = $service->insertReplies(2, $data);
        $this->assertEquals(1, count($result));
    }

    public function test_insertReplies()
    {
        $data = [
            'replies' => '1,2,3'
        ];
        $service = new ReplyService();
        $result = $service->insertReplies(2, $data);
        $this->assertEquals(true, $result);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\RiddleService;

class RiddleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_add()
    {
        $service = new RiddleService();

        $data = [
            'type' => 'Y',
            'title' => 'tit',
            'text' => 'txt',
            'explain' => 'exp',
            'hint' => 'hnt',
            'url' => 'url',
            'youtube' => 'youtube',
            'answers' => ''
        ];
        $result = $service->add($data);
        $this->assertEquals('tit', $result->title);
    }

    public function test_update()
    {
        $service = new RiddleService();

        $data = [
            'type' => 'Y',
            'title' => 'tit',
            'text' => 'txt',
            'explain' => 'exp',
            'hint' => 'hnt',
            'url' => 'url',
            'youtube' => 'youtube',
            'answers' => 'fds~dsa'
        ];
        $result = $service->update(3, $data);
        $this->assertEquals(true, $result);
    }

    public function test_delete()
    {
        $service = new RiddleService();
        $result = $service->delete(3);
        $this->assertEquals(true, $result);
    }

    public function test_getAll()
    {
        $service = new RiddleService();
        $service->delete(1);
        $service->delete(2);
        $service->delete(3);
        $result = $service->getAll();
        $this->assertEquals(25, count($result));
    }

    public function test_getAllPaginate()
    {
        $service = new RiddleService();
        $data = [
            'sort' => 'id',
            'order' => 'desc'
        ];
        $result = $service->getAllPaginate($data);
        $this->assertEquals(3, count($result));
    }

    public function test_getAllWithAnswer()
    {
        $service = new RiddleService();
        $result = $service->getAllWithAnswer(2);
        $this->assertEquals(3, count($result));
    }
}

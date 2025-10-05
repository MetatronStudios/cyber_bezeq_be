<?php
namespace App\Services;

use App\Exceptions\BusinessException;
use App\Repositories\AnswerRepository;

class AnswerService extends ParentService
{
    public function __construct()
    {
        $this->repository = new AnswerRepository();
    }

    public function insertAnswers($id_riddle, $answers)
    {
        foreach(explode('~', $answers) as $text) {
            parent::add([
                'id_riddle' => $id_riddle,
                'text' => $text
            ]);
        }
        return true;
    }

    public function deleteByRiddle($id_riddle)
    {
        $this->repository->deleteByRiddle($id_riddle);
        return true;
    }
}
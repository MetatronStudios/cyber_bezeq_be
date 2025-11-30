<?php
namespace App\Services;

use App\Repositories\RiddleRepository;
use App\Services\AnswerService;
use App\Utils\FileUtil;

class RiddleService extends ParentService
{
    protected $answerService;

    public function __construct()
    {
        $this->repository       = new RiddleRepository();
        $this->answerService    = new AnswerService();
    }

    public function getAll()
    {
        $items = $this->repository->getAll();
        if ( count($items) == 0 ) {
            $this->populate();
            return $this->getAll();
        }
        return $items;
    }

    private function populate()
    {
        $total = config('app.total_riddles', 15);
        for ($i = 1; $i <= $total; $i++) {
            $data = [   'type' => 'N',
                        'title' => 'Riddle Number '.$i,
                        'text' => '',
                        'explain' => '',
                        'hint' => '',
                        'url' => '',
                        'answers' => '',
                        'youtube' => ''];
            $this->add($data);
        }
    }

    public function add($data)
    {
        $riddle = parent::add($data);

        $this->answerService->deleteByRiddle($riddle->id);
        $this->answerService->insertAnswers($riddle->id, $data['answers']);

        return $riddle;
    }

    public function update($id, $data)
    {
        $this->answerService->deleteByRiddle($id);
        $this->answerService->insertAnswers($id, $data['answers']);
        return parent::update($id, $data);
    }

    public function delete($id)
    {
        $this->answerService->deleteByRiddle($id);
        return parent::delete($id);
    }

    public function getAllWithAnswer($id_user)
    {
        if ( $id_user == 0 ) {
            return $this->repository->getAllWithAnswerNotLogged();
        }
        else {
            return $this->repository->getAllWithAnswer($id_user);
        }
    }

    public function export()
    {
        $items = $this->repository->getAllWithAnswers();
        $out = '';
        foreach($items as $item) {
            $fields = [];
            $values = [];
            foreach($item->toArray() as $field => $value) {
                if ($field == 'answers') {
                    array_push($fields, $field);
                    $answers = [];
                    foreach($value as $answer) {
                        array_push($answers, $answer['text']);
                    }
                    array_push($values, implode('~', $answers));
                }
                else {
                    array_push($fields, $field);
                    array_push($values, $value);
                }
            }
            if ($out == '') {
                $out = '"'.implode('","', $fields).'"'."\r\n";
            }
            $out .= '"'.implode('","', str_replace("\"","\"\"",$values)).'"'."\r\n";
        }
        return FileUtil::addBom($out);
    }
}

<?php
namespace App\Services;

use App\Repositories\SolutionRepository;
use Auth;
use App\Utils\FileUtil;
use App\Services\RiddleService;
use App\Exceptions\BusinessException;

class SolutionService extends ParentService
{
    public function __construct()
    {
        $this->repository = new SolutionRepository();
    }

    public function export()
    {
        $items = $this->repository->getallWithScore();
        $out = '';
        foreach($items as $item) {
            $fields = [];
            $values = [];
            foreach($item->toArray() as $field => $value) {
                array_push($fields, $field);
                array_push($values, $value);
            }
            if ($out == '') {
                $out = '"'.implode('","', $fields).'"'."\r\n";
            }
            $out .= '"'.implode('","', $values).'"'."\r\n";
        }
        return FileUtil::addBom($out);
    }

    public function getNumberofUserSolutionsOfRiddle($user_id, $riddle_id)
    {
        $items = $this->repository->getNumberofUserSolutionsOfRiddle($user_id, $riddle_id);
        return $items;
    }

    public function getAllWiteScorePaginate($data)
    {
        $items = $this->repository->getAllWiteScorePaginate($data);
        return $items;
    }

    public function getById($id)
    {
        $item = $this->repository->getById($id);
        if ( !$item ) {
            throw new BusinessException('Error, id not found.');
        }
        return $item;
    }

    public function insertAnswer($id_user, $data)
    {
        $data['id_user'] = $id_user;

        $riddleService = new RiddleService();
        $riddle = $riddleService->getById($data['id_riddle']);

        $isCorrect = false;
        foreach ($riddle->answers as $answer) {
            if (strtolower($answer->text) == strtolower($data['answer'])) {
                $isCorrect = true;
                break;
            }
        }
        $data['is_correct'] = $isCorrect ? 1 : 0;
        if ($isCorrect)
        {
            $out = $this->repository->checkIfCorrectExists($data['id_riddle'],$data['id_user']);
            if (!$out)
            {
                $out = parent::add($data);
                $out = $out->toArray();
                $out['exp'] = $riddle->explain;
            }
            $out['exp'] = $riddle->explain;
        }
        else
        {
            $out = parent::add($data);
            $out = $out->toArray();
        }
        return (object)$out;
    }
}

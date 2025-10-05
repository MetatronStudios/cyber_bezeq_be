<?php
namespace App\Services;

use App\Repositories\ReplyRepository;
use App\Exceptions\BusinessException;
use App\Services\FactService;

class ReplyService extends ParentService
{
    public function __construct()
    {
        $this->repository = new ReplyRepository();
    }
    
    public function insertReplies($id_user, $data)
    {
        $replies = $data && $data['replies'] ? $data['replies'] : '';
        $list = explode(',', $replies);

        if (count($list) != 3) {
            throw new BusinessException('Wrong format.');
        }

        $factService = new FactService();
        $score = 0;
        foreach($list as $id_fact) {
            $fact = $factService->getById($id_fact);
            if ( $fact->is_correct ) {
                $score++;
            }
        }
        
        parent::add([
            'id_user' => $id_user,
            'answer' => $replies,
            'score' => $score
        ]);

        return true;
    }
}
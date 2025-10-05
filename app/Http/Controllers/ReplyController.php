<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReplyService;
use App\Http\Requests\ReplyRequest;
use Response;
use Auth;

/**
 * @codeCoverageIgnore
 */
class ReplyController extends ParentController
{
    public function __construct()
    {
        $this->service = new ReplyService();
    }

    public function list(Request $request)
    {
        $items = $this->service->getAllPaginate($request->all());
        return $this->respondWithData($items);
    }

    public function export()
    {
        $fileText = $this->service->export();
        $myName = 'download.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition'=>sprintf('attachment; filename="%s"', 'download.csv')
        ];
        return Response::make($fileText, 200, $headers);
    }

    public function add(ReplyRequest $request)
    {
        try {
            $id_user = Auth::id();
            $this->service->insertReplies($id_user, $request->all());
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage(), 500);
        }
        return $this->respondWithData(['message' => 'תוכלו לשגר עוד ועוד ניסיונות פתרון, אולם זכרו: כל הצעת פתרון נוספת שתשוגר, תמחק לחלוטין את ההצעה הקודמת שנשלחה!!'], 201);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiddleService;
use App\Http\Requests\RiddleRequest;
use Auth;
use Response;

/**
 * @codeCoverageIgnore
 */
class RiddleController extends ParentController
{
    public function __construct()
    {
        $this->service = new RiddleService();
    }
    
    public function add(RiddleRequest $request)
    {
        $item = $this->service->add($request->all());
        return $this->respondWithData($item, 201);
    }

    public function list(Request $request)
    {
        $items = $this->service->getAllPaginate($request->all());
        return $this->respondWithData($items);
    }
    
    public function update(RiddleRequest $request, $id)
    {
        $res = $this->service->update($id, $request->all());
        return $this->respondWithData($res);
    }

    public function get($id)
    {
        $item = $this->service->getById($id);
        return $this->respondWithData($item);
    }

    public function getAll()
    {
        $items = $this->service->getAll();
        return $this->respondWithData($items);
    }

    public function getAllWithAnswerNotLogged()
    {
        $id_user = 0;
        $items = $this->service->getAllWithAnswer($id_user);
        return $this->respondWithData($items);
    }

    public function getAllWithAnswer()
    {
        $id_user = Auth::id();
        $items = $this->service->getAllWithAnswer($id_user);
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
}
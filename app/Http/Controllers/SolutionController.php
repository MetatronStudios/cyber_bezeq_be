<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SolutionService;
use App\Http\Requests\SolutionRequest;
use Response;
use Auth;

/**
 * @codeCoverageIgnore
 */
class SolutionController extends ParentController
{
    public function __construct()
    {
        $this->service = new SolutionService();
    }

    public function list(Request $request)
    {
        $items = $this->service->getAllWiteScorePaginate($request->all());
        return $this->respondWithData($items);
    }

    public function add(SolutionRequest $request)
    {
        // $end = mktime(23,59,59,2,17,2024);
        // if (time()>$end)
        // {
        //     return $this->respondWithData(["msg"=>"The riddle contest has ended. Thank you for participating."], 422);
        // }
        $id_user = Auth::id();
        // $retries = $this->service->getNumberofUserSolutionsOfRiddle($id_user, $request->id_riddle);
        // if ($retries >= 5)
        // {
        //     return $this->respondWithData(["msg"=>"You have already attempt to answer this riddle 5 times."], 201);
        // }

        $item = $this->service->insertAnswer($id_user, $request->all());
        return $this->respondWithData($item, 201);
    }

    public function update(SolutionRequest $request, $id)
    {
        $this->service->update($id, $request->all());
        return $this->respondWithData();
    }

    public function delete($id)
    {
        $this->service->delete($id);
        return $this->respondWithData();
    }

    public function get($id)
    {
        $item = $this->service->getById($id);
        return $this->respondWithData($item);
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

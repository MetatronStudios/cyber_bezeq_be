<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FactService;
use App\Http\Requests\FactRequest;
use Illuminate\Support\Facades\Response;

/**
 * @codeCoverageIgnore
 */
class FactController extends ParentController
{
    public function __construct()
    {
        $this->service = new FactService();
    }

    public function list(Request $request)
    {
        cache()->forget('all_facts');
        $items = cache()->remember('all_facts', 9000, function () {
            return $this->service->getAll();
        });
        $items = $this->service->getAllPaginate($request->all());
        return $this->respondWithData($items);
    }

    public function update(FactRequest $request, $id)
    {
        cache()->forget('all_facts');
        $this->service->update($id, $request->all());
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

    public function getAll()
    {
        // $items = $this->service->getAll();
        $items = cache()->remember('all_facts', 9000, function () {
            return $this->service->getAll();
        });
        return $this->respondWithData($items);
    }
}

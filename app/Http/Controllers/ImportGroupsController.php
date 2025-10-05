<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImportGroupsService;
use App\Http\Requests\ImportGroupsRequest;
use Auth;
use Response;

/**
 * @codeCoverageIgnore
 */
class ImportGroupsController extends ParentController
{
    public function __construct()
    {
        $this->service = new ImportGroupsService();
    }

    public function list()
    {
        $items = $this->service->list();
        return $this->respondWithData($items);
    }
    
    public function addNew(Request $request)
    {
        $id = $request->id;
        $item = $this->service->addNew($id);
        return $this->respondWithData($item, 201);
    }
    
}
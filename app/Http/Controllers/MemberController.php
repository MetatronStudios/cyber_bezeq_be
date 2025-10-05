<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Http\Requests\MemberRequest;
use Response;

/**
 * @codeCoverageIgnore
 */
class MemberController extends ParentController
{
    public function __construct()
    {
        $this->service = new MemberService();
    }

    public function list(Request $request)
    {
        $items = $this->service->getAllPaginate($request->all());
        return $this->respondWithData($items);
    }

    public function get($id)
    {
        $item = $this->service->getById($id);
        return $this->respondWithData($item);
    }

    public function getByIdUser()
    {
        $id_user = Auth::id();
        $item = $this->service->getByIdUser($id_user);
        return $this->respondWithData($item);
    }

    public function getAll()
    {
        $items = $this->service->getAll();
        return $this->respondWithData($items);
    }
}
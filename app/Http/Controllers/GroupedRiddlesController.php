<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\GroupedRiddlesService;
use App\Http\Requests\GroupedRiddlesRequest;
use Illuminate\Support\Facades\Cache;
use Auth;
use Response;
/**
 * @codeCoverageIgnore
 */
class GroupedRiddlesController extends ParentController
{
    public function __construct()
    {
        $this->service = new GroupedRiddlesService();
    }

    public function list(Request $request)
    {
        $items = $this->service->getAllPaginate($request->all());
        return $this->respondWithData($items);
    }

    public function add(GroupedRiddlesRequest $request)
    {
        $start_at = date("Y-m-d H:i:s",strtotime($request->start_at));
        $request->merge(['start_at' => $start_at]);
        $item = $this->service->add($request->all());
        return $this->respondWithData($item, 201);
    }

    public function update(GroupedRiddlesRequest $request, $id)
    {
        $start_at = date("Y-m-d H:i:s",strtotime($request->start_at));
        $request->merge(['start_at' => $start_at]);
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

    public function getPlayableGroupsNotLogged()
    {
        // $id_user = 0;
        // $items = $this->service->getPlayableGroups($id_user);
        $items = Cache::remember('playableGroupsRiddles', 120, function () {
            return $this->service->getPlayableGroups(0);
        });
        return $this->respondWithData($items);
    }

    public function getPlayableGroups()
    {
        $id_user = Auth::id();
        $items = $this->service->getPlayableGroups($id_user);
        return $this->respondWithData($items);
    }
}

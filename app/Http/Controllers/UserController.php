<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Utils\FileUtil;
use Illuminate\Support\Facades\Response;

/**
 * @codeCoverageIgnore
 */
class UserController extends ParentController
{
    public function __construct()
    {
        $this->service = new UserService();
    }

    public function list(Request $request)
    {
        $items = $this->service->getAllPaginate($request->all());
        return $this->respondWithData($items);
    }

    public function winners(Request $request)
    {
        $items = $this->service->getWinners($request->all());
        return $this->respondWithData($items);
    }

    public function winners_export(Request $request)
    {
        $items = $this->service->getWinnersExport();
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

        $fileText = FileUtil::addBom($out);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition'=>sprintf('attachment; filename="%s"', 'winners.csv')
        ];
        return Response::make($fileText, 200, $headers);
    }


    public function winnersMembers(Request $request)
    {
        $items = $this->service->getWinnersMembers($request->all());
        return $this->respondWithData($items);
    }

    public function finalist_winners(Request $request)
    {
        $items = $this->service->getFinalistWinners($request->all());
        return $this->respondWithData($items);
    }

    public function finalistWinners_export()
    {
        $items = $this->service->getFinalistWinnersExport();
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

        $fileText = FileUtil::addBom($out);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition'=>sprintf('attachment; filename="%s"', 'winners.csv')
        ];
        return Response::make($fileText, 200, $headers);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConfigService;
use App\Http\Requests\ConfigRequest;
use Illuminate\Support\Facades\Cache;

/**
 * @codeCoverageIgnore
 */
class ConfigController extends ParentController
{
    public function __construct()
    {
        $this->service = new ConfigService();
    }

    public function get($id)
    {
        $item = $this->service->getById($id);
        return $this->respondWithData($item);
    }

    public function update(ConfigRequest $request, $id)
    {
        $res = $this->service->update($id, $request->all());
        return $this->respondWithData($res);
    }

    public function getTicker()
    {
        include app_path() . '/Utils/FinalData.php';
        $now    = time();
        $item = [];

        $ticker = Cache::remember('ticker', 120, function () {
            $tik = $this->service->getById(1);
            return $tik->text;
        });
        $item["ticker"] = $ticker;

        $start  = $DISABLED_START ? strtotime($DISABLED_START) : $now-1;
        $end    = $DISABLED_END ? strtotime($DISABLED_END) : $now+1;

        $item["isFinal"] =  (( $now < $start ) || ( $now > $end )) ? false : true;
        $item["startTimer"] = 0;
        $item["endTimer"] = 0;

        if ($item["isFinal"]) {
            $start = strtotime($ENABLED_START) + rand(0, 10); // add 0-5 seconds for concurrent control
            $end = strtotime($ENABLED_END) ;
            $item["startTimer"] = ($start - $now) <= 0 ? -10 : ($start - $now) * 1000;
            $item["endTimer"] = ($end - $now) * 1000;

        }
        return $this->respondWithData($item);
    }

    public static function is_final()
    {
        include app_path() . '/Utils/FinalData.php';
        $now    = time();
        $start  = $DISABLED_START ? strtotime($DISABLED_START) : $now-1;
        $end    = $DISABLED_END ? strtotime($DISABLED_END) : $now+1;
        return (!(($now < $start) || ($now > $end )));
    }

    public function get_version()
    {
        $ver = Cache::remember('app_version', 120, function () {
            return trim(file_get_contents(public_path('counter.txt')));
        });
        return response()->json(['version' => $ver]);
    }

    public function simple_load_test() {
        return response()->json([
                'ok' => true,
                'time' => microtime(true),
            ], 200);
    }

    public function load_test() {
        // check if remote address is local
        $remoteAddress = request()->ip();
        if ( $remoteAddress != '127.0.0.1' && $remoteAddress != '::1' && $remoteAddress != '99.80.225.64') {
            return response()->json(['errors' => 'not allowed from ' . $remoteAddress])->setStatusCode(401);
        }
        $OUT = [];
        $ver = Cache::remember('app_version', 120, function () {
            return trim(file_get_contents(public_path('counter.txt')));
        });
        $OUT["version"] = $ver;

        include app_path() . '/Utils/FinalData.php';
        $now    = time();
        $item = [];

        $ticker = Cache::remember('ticker', 120, function () {
            $tik = $this->service->getById(1);
            return $tik->text;
        });
        $item["ticker"] = $ticker;

        $start  = $DISABLED_START ? strtotime($DISABLED_START) : $now-1;
        $end    = $DISABLED_END ? strtotime($DISABLED_END) : $now+1;

        $item["isFinal"] =  (( $now < $start ) || ( $now > $end )) ? false : true;
        $item["startTimer"] = 0;
        $item["endTimer"] = 0;

        if ($item["isFinal"]) {
            $start = strtotime($ENABLED_START) + rand(0, 10); // add 0-5 seconds for concurrent control
            $end = strtotime($ENABLED_END) ;
            $item["startTimer"] = ($start - $now) <= 0 ? -10 : ($start - $now) * 1000;
            $item["endTimer"] = ($end - $now) * 1000;

        }
        $OUT["item"] = $item;

        $factsservice = new \App\Services\FactService();
        $OUT["facts"] = cache()->remember('all_facts_test', 120, function () use ($factsservice) {
            $facts = $factsservice->getAll();
            // sinse its only for stress test, need to send the same weight of data but without the real data
            $output = [];
            foreach ($facts as $key => $fact) {
                $titleLen = strlen($fact->title);
                $output[] = [
                    'id' => $fact->id,
                    'title' => str_repeat('X', $titleLen),
                ];

            }
            return $output;
        });
        return $this->respondWithData($OUT);
    }
}

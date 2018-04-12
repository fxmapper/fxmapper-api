<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
//use Illuminate\Support\Facades\DB;
use App\ExchangeRateModel;
use App\ApiKeyModel;
use App\RequestLog;

class ExchangeController extends Controller
{

    public function __construct()
    {
        $this->rateLookup = new ExchangeRateModel;

    }

    public function index(Request $request, $source, $target, $key = null, $options = null){
        $data = $this->rateLookup->rate($source, $target);
        $options = explode(',',$options);
        $log = new RequestLog;

        if(null === $key){
            $key = '0';
        }

        // initialize logger
        $log->source = strtoupper($source);
        $log->target = strtoupper($target);
        $log->key = $key;
        $log->user_agent = $request->userAgent();
        $log->user_ip = $request->ip();

        if(!$this->isApiValid($key)){
            $log->save();
            sleep(3);
            return (array)['price' => $data['price']];
        }
        $log->save();

        if(in_array('csv', $options)){
            return implode(',', (array)$data);
        }

        return Response::json($data,  $status=200, $headers=[], $options=JSON_PRETTY_PRINT);

    }

    private function isApiValid($api){
        return (bool)count(ApiKeyModel::where([
            'key' => $api,
            'active' => 1
        ])->get()
        );
    }
}

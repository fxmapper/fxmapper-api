<?php

namespace App\Http\Controllers\Version1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exchanger;
use App\ApiKeyModel;
use App\RequestLog;

class ExchangeEndpoint extends Controller
{

    public function index(Request $request, $source, $target, $key, $options = null){
        $exchanger = new Exchanger;

//        This should be here for validation, but the route doesn't bring people here unless they provided source and target
//          Solution 1: Make them optional parameters
//          Solution 2: Have new routes for /v1/exchange/ and /v1/exchange/{source}
//        if(!$source){
//            return response(json_encode(['You must provide source and target currency codes'], JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);
//        }
//
//        if(!$target){
//            return response(json_encode(['You must provide a target currency code'], JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);
//        }

        if(null === $key){
            $key = '0';
        }

        $data = $exchanger->rate($source, $target);
        $options = explode(',',$options);

        $valid = ApiKeyModel::checkIfValid($key);
        $log = new RequestLog;

        // initialize logger
        $log->source = strtoupper($source);
        $log->target = strtoupper($target);
        $log->key = $key;
        $log->user_ip = $request->ip();
        $log->save();

        if(!$valid) {
            sleep(5);
            return response(json_encode(['price' => $data['price']] , JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);
        }

        if(in_array('csv', $options)){
            return response(implode(', ', (array)$data), 200, ['Content-Type' => 'text/text']);
        }

        return response(json_encode($data, JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);

    }

}

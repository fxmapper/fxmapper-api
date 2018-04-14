<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\ExchangeRateModel;
use App\ApiKeyModel;
use App\RequestLog;

class ExchangeController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request, $source, $target, $key, $options = null){
        $exchanger = new ExchangeRateModel;

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
            return implode(',', (array)$data);
        }

        return response(json_encode($data, JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);

    }

}

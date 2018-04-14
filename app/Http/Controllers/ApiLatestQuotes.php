<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CurrencyModel;
use App\ExchangeRateModel;
use Illuminate\Support\Facades\Response;
use App\ApiKeyModel;
use Carbon\Carbon;
use App\RequestLog;
use Illuminate\Support\Facades\DB;

class ApiLatestQuotes extends Controller
{
    //
    private $key;

    public function __construct(Request $request)
    {
        $this->key = $request->segment(3);
    }



    public function index(Request $request){
        $log = new RequestLog;
        $rates = new ExchangeRateModel;

        $apiKey = ApiKeyModel::where(['active' => 1, 'key' => $this->key])->first();

        if(!$apiKey){
            return [
                'status' => 'error - invalid API key'
            ];
        };

        $currencies = CurrencyModel::getActive();

        $data['status'] = 'OK';
        $data['base'] = $apiKey->base;
        $data['date'] = Carbon::now()->toDateTimeString();

        foreach($currencies as $c){
            $data['rates'][$c->code] = $rates->rate($apiKey->base, $c->code)['price'];
        }

        // Log result
        // initialize logger
        $log->source = strtoupper($data['base']);
        $log->target = strtoupper('latest');
        $log->key = $apiKey->key;
        $log->user_ip = $request->ip();
        $log->save();

        return response(json_encode($data, JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);

    }
}

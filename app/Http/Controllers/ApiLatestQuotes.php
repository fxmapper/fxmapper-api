<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CurrencyModel;
use App\ExchangeRateModel;
use Illuminate\Support\Facades\Response;
use App\ApiKeyModel;
use Carbon\Carbon;
use App\RequestLog;

class ApiLatestQuotes extends Controller
{
    //

    public function index(Request $request, $key)
    {
        $log = new RequestLog;

        $rates = new ExchangeRateModel;
        $user = ApiKeyModel::where([
            'key' => $key,
            'active' => 1,
        ])->first();

        $count = count($user);

        if($count == 0){
            return Response::json("Unauthorized API Key",  $status=200, $headers=[], $options=JSON_PRETTY_PRINT);
        }

        $currencies = CurrencyModel::where(['active' => 1])
            ->orderBy('name', 'asc')
            ->get();

        $data['base'] = $user->base;
        $data['date'] = Carbon::now()->toDateTimeString();

        foreach($currencies as $c){
            $data['rates'][$c->code] = $rates->rate($user->base, $c->code)['price'];
        }

        // Log result
        // initialize logger
        $log->source = strtoupper($data['base']);
        $log->target = strtoupper('latest');
        $log->key = $user->key;
        $log->user_agent = $request->userAgent();
        $log->user_ip = $request->ip();
        $log->save();

        return Response::json($data,  $status=200, $headers=[], $options=JSON_PRETTY_PRINT);
    }

}

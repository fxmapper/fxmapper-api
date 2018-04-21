<?php

namespace App\Http\Controllers\Version1;

use Illuminate\Http\Request;
use App\CurrencyModel;
use App\Exchanger;
use App\ApiKeyModel;
use Carbon\Carbon;

class LatestQuotes extends ControllerV1
{
    public function index(Request $request, Exchanger $exchanger, $key){
        $api = ApiKeyModel::where(['active' => 1, 'key' => $key])->first();

        if(!ApiKeyModel::checkIfValid($key)){
            return $this->badApiKey();
        }

        $currencies = CurrencyModel::getActive();

        $data['status'] = 'OK';
        $data['base'] = $api->base;
        $data['date'] = Carbon::now()->toDateTimeString();

        foreach($currencies as $c){
            $data['rates'][$c->code] = $exchanger->quick($api->base, $c->code)->price;
        }

        // Log result
        // initialize logger
        $this->logger->source = strtoupper($data['base']);
        $this->logger->target = strtoupper('latest');
        $this->logger->key = trim($key);
        $this->logger->user_ip = $request->ip();
        $this->logger->save();

        return response(json_encode($data, JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);

    }

}

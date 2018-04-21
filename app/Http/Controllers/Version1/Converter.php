<?php

namespace App\Http\Controllers\Version1;

use Illuminate\Http\Request;
use App\Http\Controllers\Version1\Errors;
use App\CurrencyModel;
use App\Exchanger;
use App\ApiKeyModel;
use Carbon\Carbon;

class Converter extends ControllerV1
{
    //

    public function index(Request $request, Exchanger $exchanger, Errors $error, $quantity = null, $source = null, $target = null, $key = null){

        $source = CurrencyModel::where(['code' => $source])->first();
        $target = CurrencyModel::where(['code' => $target])->first();

        if(null == $source || null ==  $target) {
            return $error->symbolInvalid();
        }

        if(!ApiKeyModel::checkIfValid($key)){
            return $error->badApiKey();
        }

        $this->logger->key = $key;
        $this->logger->source = $source->code;
        $this->logger->target = $target->code;
        $this->logger->user_ip = $request->ip();
        $this->logger->save();

        $exchange = $exchanger->quick($source->code, $target->code);
        $price = round($exchange->price * $quantity, $target->decimal_places);

        return response(json_encode(['status' => 'OK', 'price' => $price], JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);
    }

}

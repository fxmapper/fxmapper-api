<?php

namespace App\Http\Controllers\Version1;

use Illuminate\Http\Request;
use App\Exchanger;
use App\ApiKeyModel;
use App\CurrencyModel;

class ExchangeEndpoint extends ControllerV1
{
    public function index(Request $request, Exchanger $exchanger, $source, $target, $key, $options = null){
        $data = $exchanger->quick($source, $target);
        $options = explode(',',$options);

        $source = CurrencyModel::where(['code' => $source])->first();
        $target = CurrencyModel::where(['code' => $target])->first();

        if(null == $source || null ==  $target) {
            return $this->symbolsInvalid();
        }

        // initialize logger
        $this->logger->source = strtoupper($source->code);
        $this->logger->target = strtoupper($target->code);
        $this->logger->user_ip = $request->ip();
        $this->logger->key = $key;

        if(!ApiKeyModel::checkIfValid($key)){
            sleep(5);
            $this->logger->key = 0;
        }

        $this->logger->save();

        if(in_array('csv', $options)){
            return response(implode(', ', (array)$data), 200, ['Content-Type' => 'text/text']);
        }

        return response(json_encode($data, JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);
    }

    public function symbolsInvalid(){
        $data = [
            'status' => 'Fail',
            'reason' => 'One or both of the currency symbols provided are not valid.',
        ];

        return response(json_encode($data, JSON_PRETTY_PRINT), 403, ['Content-Type' => 'application/json']);

    }
}

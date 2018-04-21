<?php

namespace App\Http\Controllers\Version1;

class Errors extends ControllerV1 {

    public function badApiKey(){
        $data = [
            'status' => 'Fail',
            'reason' => 'Invalid API key provideddddd'
        ];

        return response(json_encode($data, JSON_PRETTY_PRINT), 403, ['Content-Type' => 'application/json']);
    }

    public function error(){
        $data = [
            'status' => 'Fail',
            'reason' => 'One or more required parameters are missing. Please review the documentation, it\'s easy!',
        ];

        return response(json_encode($data, JSON_PRETTY_PRINT), 403, ['Content-Type' => 'application/json']);
    }

    public function missingParams(){
        $data = [
            'status' => 'Fail',
            'reason' => 'One or more required parameters are missing. Please review the documentation, it\'s easy!',
        ];

        return response(json_encode($data, JSON_PRETTY_PRINT), 403, ['Content-Type' => 'application/json']);
    }

    public function symbolsInvalid(){
        $data = [
            'status' => 'Fail',
            'reason' => 'One or both of the currency symbols provided are not valid.',
        ];

        return response(json_encode($data, JSON_PRETTY_PRINT), 403, ['Content-Type' => 'application/json']);

    }
}
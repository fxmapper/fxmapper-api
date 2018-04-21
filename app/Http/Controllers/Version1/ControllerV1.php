<?php

namespace App\Http\Controllers\Version1;

use App\Http\Controllers\Controller;
use App\RequestLog;

class ControllerV1 extends Controller {
    protected $logger;

    public function __construct(RequestLog $logger){
        $this->logger = $logger;
    }
}

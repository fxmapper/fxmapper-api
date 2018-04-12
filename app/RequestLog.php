<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    //
    protected $table = 'request_log';

    protected $primaryKey = 'request_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'key',
        'source',
        'target',
        'useragent',
        'user_ip',
    ];

    public function key()
    {
        return $this->belongsTo('App\ApiKeyModel', 'key', 'key');
    }
}
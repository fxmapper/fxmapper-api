<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKeyModel extends Model
{
    //

    use SoftDeletes;

    protected $table = 'api_keys';

    protected $primaryKey = 'api_id';

    public $timestamps = true;

    const CREATED_AT = 'date_created';

    const UPDATED_AT = 'date_modified';

    protected $fillable = [
        'user_id',
        'base',
        'key',
        'active',
        'hits',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->key = 'a-' . bin2hex(random_bytes(8));
            $model->base = 'USD';
            $model->active = 1;
            $model->hits = 0;
        });
    }

    public function requests()
    {
        return $this->hasMany('App\RequestLog', 'key', 'key');
    }

}

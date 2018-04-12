<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyModel extends Model
{
    //
    protected $table = 'currencies';

    protected $primaryKey = 'currency_id';

    public $timestamps = false;

    protected $fillable = [
        'source_id',
        'code',
        'name',
        'symbol',
        'html',
        'spacer',
        'symbol_position',
        'thousands',
        'decimal',
        'decimal_places',
        'active',
        'wiki',
    ];


    public function source() {
        return $this->belongsTo('App\SourcesModel', 'source_id', 'source_id');
    }

    public static function getActive() {
        return static::where(['active' => true])
            ->orderBy('name', 'ASC')
            ->get();
    }

    public static function getOne($code) {
        return static::where(['active' => 1, 'code' => $code])
            ->first();
    }

}

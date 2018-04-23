<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\CurrencyModel;

class Exchanger extends Model
{
    //

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function quick($source, $target){
        $result1 = $this->lookUpCurrencyPair('EUR', $source)->first();
        $result2 = $this->lookUpCurrencyPair('EUR', $target)->first();

        $price = $result1->price / $result2->price;

        $result['source'] = strtoupper($source);
        $result['destination'] = strtoupper($target);
        $result['price'] = sprintf("%.15f", 1/$price);
        $result['date'] = $result1->date_created;

        return (object)$result;

    }

    public function lookUpCurrencyPair($source, $destination){
        return DB::table('rates')->where(
            [
                'source_currency' => $source,
                'dest_currency' => $destination
            ])
            ->orderBy('rate_id', 'desc')
            ->limit(1);
    }

}

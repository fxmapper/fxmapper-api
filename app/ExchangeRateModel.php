<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExchangeRateModel extends Model
{
    //

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function rate($source, $destination){
        // Check input for obvious errors such as string length, invalid characters
        $error = false;
        $result = [];
        $source = normalize($source);
        $destination = normalize($destination);

        if(is_object($source)) {
            $error = true;
            $result['error'] = "Source error: " . $source->error;
        }

        if(is_object($destination)) {
            $error = true;
            $result['error'] = "Destination Currency error: " . $destination->error;
        }

        if(!$error) {
            $sourceQuery = $this->lookUpCurrencyPair('EUR', $source);
            $destinationQuery = $this->lookUpCurrencyPair('EUR', $destination);

            if($sourceQuery->count() == 0){
                $result['error'] = "Source error: no record matched";
            } elseif($destinationQuery->count() == 0){
                $result['error'] = "Destination error: no record found";
            } else {
                $result1 = $sourceQuery->first();
                $result2 = $destinationQuery->first();

                $price = $result1->price / $result2->price;

                $result['source'] = $source;
                $result['destination'] = $destination;
                $result['price'] = sprintf("%.15f", 1/$price);
                $result['asOf'] = $result1->date_created;
            }
        }

        return $result;

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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyFormat extends Model
{
    //
    public $attributes;

    public $include_symbol = false;

    public $include_code = false;

    public $code;

    public $symbol;

    public $symbol_position;

    public $thousands;

    public $spacer;

    public $decimal;

    public $decimal_places;

    public function __construct($currency){

        $this->thousands = $currency->thousands;
        $this->decimal = $currency->decimal;
        $this->decimal_places = $currency->decimal_places;
        $this->symbol = $currency->html;  // Set to the HTML Code for the Currency's symbol
        $this->symbol_position = $currency->symbol_position;
        $this->spacer = $currency->spacer;
        $this->code = $currency->code;

    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    public function do($value){
        $value = $this->format($value);

        if($this->include_symbol) {
            $value = $this->addSymbol($value);
        }

        if($this->include_code) {
            $value = $value . ' ' . $this->code;
        }
        return $value;

    }

    private function format($value){
        return number_format($value, $this->decimal_places, $this->decimal, $this->thousands);
    }

    private function addSymbol($value){
        $spacer = '';

        if($this->spacer == true){
            $spacer = ' ';
        }

        if($this->symbol_position == "before"){
            return $this->symbol . $spacer . $value;
        }
        return $value . $spacer . $this->symbol;
    }

}

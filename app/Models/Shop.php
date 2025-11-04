<?php

class Shop {
    public $id;
    public $seller_id;
    public $name;
    public $address;
    public function __construct($seller_id) {
        $this->seller_id = $seller_id;
    }

    public static function get($seller_id) {
        
    }
}
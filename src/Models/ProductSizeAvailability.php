<?php

namespace Cdcrane\Dwes\Models;

class ProductSizeAvailability {

    private $size;
    private $count;

    public function __construct($size, $count){
        $this->size = $size;
        $this->count = $count;
    }

    public function getSize() {
        return $this->size;
    }

    public function getCount() {
        return $this->count;
    }

}
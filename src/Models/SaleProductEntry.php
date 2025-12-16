<?php

namespace Cdcrane\Dwes\Models;

class SaleProductEntry {

    private $prodName;
    private $prodId;
    private $prodSize;
    private $quantity;

    public function __construct($name, $id, $size, $quant)
    {
        $this->prodName = $name;
        $this->prodId = $id;
        $this->prodSize = $size;
        $this->quantity = $quant;
    }

    public function getName() {
        return $this->prodName;
    }

    public function getProdId() {
        return $this->prodId;
    }

    public function getSize() {
        return $this->prodSize;
    }

    public function getQuant() {
        return $this->quantity;
    }

}
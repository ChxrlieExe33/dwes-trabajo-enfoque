<?php

namespace Cdcrane\Dwes\models;

class CartEntryView {
    
    private $quantity;
    private $size;
    private $prodName;
    private $entryTotal; # Quantity * unit price

    public function __construct($quantity, $size, $prodName, $entryTotal)
    {
        $this->quantity = $quantity;
        $this->size = $size;
        $this->prodName = $prodName;
        $this->entryTotal = $entryTotal;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getSize() {
        return $this->size;
    }

    public function getProdName() {
        return $this->prodName;
    }

    public function getEntryTotal() {
        return $this->entryTotal;
    }
}
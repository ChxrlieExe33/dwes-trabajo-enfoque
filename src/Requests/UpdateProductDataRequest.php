<?php

namespace Cdcrane\Dwes\Requests;

class UpdateProductDataRequest {

private $name;
    private $description;
    private $price;
    private $colour;
    private $factoryName;

    public function __construct($name, $desc, $price, $colour, $facName)
    {
        $this->name = $name;
        $this->description = $desc;
        $this->price = $price;
        $this->colour = $colour;
        $this->factoryName = $facName;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function getPrice() {
        return $this->price;
    }

    public function getColour() {
        return $this->colour;
    }

    public function getFactoryName() {
        return $this->factoryName;
    }

}
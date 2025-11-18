<?php

namespace Cdcrane\Dwes\requests;

class SaveNewProductRequest {

    private $name;
    private $description;
    private $price;
    private $colour;
    private $factoryName;
    private $imagesFromForm;

    public function __construct($name, $desc, $price, $colour, $facName, $imgsFromForm)
    {
        $this->name = $name;
        $this->description = $desc;
        $this->price = $price;
        $this->colour = $colour;
        $this->factoryName = $facName;
        $this->imagesFromForm = $imgsFromForm;
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

    public function getImages() {
        return $this->imagesFromForm;
    }
}
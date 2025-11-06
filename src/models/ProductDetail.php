<?php

namespace Cdcrane\Dwes\Models;

class ProductDetail {

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;

    private $color;
    private $fabricante;

    /**
     * @param $id
     * @param $nombre
     * @param $descripcion
     * @param $precio
     * @param $color
     * @param $fabricante
     */
    public function __construct($id, $nombre, $descripcion, $precio, $color, $fabricante)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->color = $color;
        $this->fabricante = $fabricante;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return mixed
     */
    public function getFabricante()
    {
        return $this->fabricante;
    }


}
<?php

class HomepageProduct {

    private $id;
    private $nombre;
    private $precio;
    private $nombreImagen;

    /**
     * @param $id
     * @param $nombre
     * @param $precio
     * @param $nombreImagen
     */
    public function __construct($id, $nombre, $precio, $nombreImagen)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->nombreImagen = $nombreImagen;
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
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @return mixed
     */
    public function getNombreImagen()
    {
        return $this->nombreImagen;
    }


}
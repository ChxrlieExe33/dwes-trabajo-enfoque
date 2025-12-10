<?php

namespace Cdcrane\Dwes\Models;

class UserProfile {

    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $fechaNacimiento;
    private $direccion_entrega;
    private $ciudad_entrega;
    private $provincia_entrega;
    private $direccion_facturacion;
    private $ciudad_facturacion;
    private $provincia_facturacion;

    /**
     * @param $id
     * @param $nombre
     * @param $apellidos
     * @param $email
     * @param $fechaNacimiento
     * @param $direccion_entrega
     * @param $ciudad_entrega
     * @param $provincia_entrega
     * @param $direccion_facturacion
     * @param $ciudad_facturacion
     * @param $provincia_facturacion
     */
    public function __construct($id, $nombre, $apellidos, $email, $fechaNacimiento, $direccion_entrega, $ciudad_entrega, $provincia_entrega, $direccion_facturacion, $ciudad_facturacion, $provincia_facturacion)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->direccion_entrega = $direccion_entrega;
        $this->ciudad_entrega = $ciudad_entrega;
        $this->provincia_entrega = $provincia_entrega;
        $this->direccion_facturacion = $direccion_facturacion;
        $this->ciudad_facturacion = $ciudad_facturacion;
        $this->provincia_facturacion = $provincia_facturacion;
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
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * @return mixed
     */
    public function getDireccionEntrega()
    {
        return $this->direccion_entrega;
    }

    /**
     * @return mixed
     */
    public function getCiudadEntrega()
    {
        return $this->ciudad_entrega;
    }

    /**
     * @return mixed
     */
    public function getProvinciaEntrega()
    {
        return $this->provincia_entrega;
    }

    /**
     * @return mixed
     */
    public function getDireccionFacturacion()
    {
        return $this->direccion_facturacion;
    }

    /**
     * @return mixed
     */
    public function getCiudadFacturacion()
    {
        return $this->ciudad_facturacion;
    }

    /**
     * @return mixed
     */
    public function getProvinciaFacturacion()
    {
        return $this->provincia_facturacion;
    }


}
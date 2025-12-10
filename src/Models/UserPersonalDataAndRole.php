<?php

namespace Cdcrane\Dwes\Models;

class UserPersonalDataAndRole {

    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $admin;

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
    public function __construct($id, $nombre, $apellidos, $email, $admin)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->admin = $admin;
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
    public function isAdmin()
    {
        return $this->admin;
    }


}
<?php

class RegisterAccountRequest {

    private $nombre;
    private $apellido;
    private $email;
    private $contrasena;

    private $direccion;
    private $ciudad;
    private $provincia;

    /**
     * @param $nombre string Primer nombre
     * @param $apellido string Apellidos
     * @param $email string Correo electronico
     * @param $contrasena string Contraseña
     * @param $direccion string Dirección
     * @param $ciudad string Ciudad
     * @param $provincia string Provincia
     */
    public function __construct($nombre, $apellido, $email, $contrasena, $direccion, $ciudad, $provincia)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->provincia = $provincia;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getContrasena(): string
    {
        return $this->contrasena;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    public function getProvincia(): string
    {
        return $this->provincia;
    }


}

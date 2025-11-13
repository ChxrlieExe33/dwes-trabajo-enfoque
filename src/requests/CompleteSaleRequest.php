<?php 

namespace Cdcrane\Dwes\requests;

class CompleteSaleRequest {

    private $userId;
    private $saleDate;
    private $dirreccionEntrega;
    private $ciudadEntrega;
    private $provinciaEntrega;
    private $dirreccionFac;
    private $ciudadFac;
    private $provinciaFac;

    public function __construct($userId, $saleDate, $dirEnt, $ciuEnt, $provEnt, $dirFac, $ciuFac, $provFac){
        $this->userId = $userId;
        $this->saleDate = $saleDate;
        $this->dirreccionEntrega = $dirEnt;
        $this->ciudadEntrega = $ciuEnt;
        $this->provinciaEntrega = $provEnt;
        $this->dirreccionFac = $dirFac;
        $this->ciudadFac = $ciuFac;
        $this->provinciaFac = $provFac;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getSaleDate() {
        return $this->saleDate;
    }

    public function getDireccionEnt() {
        return $this->dirreccionEntrega;
    }

    public function getCiudadEnt() {
        return $this->ciudadEntrega;
    }

    public function getProvinciaEnt() {
        return $this->provinciaEntrega;
    }

    public function getDireccionFac() {
        return $this->dirreccionFac;
    }

    public function getCiudadFac() {
        return $this->ciudadFac;
    }

    public function getProvinciaFac() {
        return $this->provinciaFac;
    }
}
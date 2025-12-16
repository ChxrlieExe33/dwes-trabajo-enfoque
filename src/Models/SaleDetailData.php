<?php

namespace Cdcrane\Dwes\Models;

class SaleDetailData {

    private $saleId;
    private $date;
    private $delAddr;
    private $delCiu;
    private $delProv;
    private $facAddr;
    private $facCiu;
    private $facProv;
    private $total;

    public function __construct($id, $date, $delA, $delC, $delP, $facA, $facC, $facP, $tot)
    {
        $this->saleId = $id;
        $this->date = $date;
        $this->delAddr = $delA;
        $this->delProv = $delP;
        $this->delCiu = $delC;
        $this->facAddr = $facA;
        $this->facCiu = $facC;
        $this->facProv = $facP;
        $this->total = $tot;
    }

    public function getSaleId(){
        return $this->saleId;
    }

    public function getDate(){
        return $this->date;
    }
    public function getDelAddr(){
        return $this->delAddr;
    }
    public function getDelCiu(){
        return $this->delCiu;
    }
    public function getDelProv(){
        return $this->delProv;
    }
    public function getFacAddr(){
        return $this->facAddr;
    }
    public function getFacCiu(){
        return $this->facCiu;
    }
    public function getFacProv(){
        return $this->facProv;
    }
    public function getTotal(){
        return $this->total;
    }

}
<?php 

namespace Cdcrane\Dwes\Models;

class SaleListView {

    private $id;
    private $date;
    private $total;
    private $provinciaEntrega;

    public function __construct($id, $date, $total, $provEntrega){

        $this->id = $id;
        $this->date = $date;
        $this->total = $total;
        $this->provinciaEntrega = $provEntrega;
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getProvEntrega() {
        return $this->provinciaEntrega;
    }

}
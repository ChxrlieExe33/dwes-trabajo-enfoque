<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\ProductService;

session_start();

AuthUtils::restrictPageAdminOnly();

if (!isset($_GET['prodId']) || !isset($_GET['size']) ||!isset($_GET['count'])) {
    header('Location: productos.php');
}

ProductService::addStockOfProduct($_GET['prodId'], $_GET['count'],  $_GET['size']);

header('Location: producto.php?id=' . $_GET['prodId']);
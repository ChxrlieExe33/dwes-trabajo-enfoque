<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\ProductService;

session_start();

AuthUtils::restrictPageAdminOnly();

if(!isset($_GET['id'])) {
    header("Location: productos.php");
}

ProductService::deleteProduct($_GET['id']);

header("Location: productos.php");
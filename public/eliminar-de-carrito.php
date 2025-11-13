<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Services\CarritoService;
use Cdcrane\Dwes\Utils\AuthUtils;

AuthUtils::checkLoginRedirectToLogin();

session_start();

if (!isset($_GET['prodID']) || !isset($_GET['size'])){ 
    header("Location: index.php");
}

CarritoService::removeFromCart($_GET['prodID'], $_GET['size']);

header("Location: micarrito.php");
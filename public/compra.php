<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\SaleService;

AuthUtils::checkLoginRedirectToLogin();

if (!isset($_GET['id'])) {
    header("Location: index.php");
}

$saleData = SaleService::getSaleDetailById($_GET['id']);
$saleProds = SaleService::getSaleProductsBySaleId($_GET['id']);

var_dump($saleData);
var_dump($saleProds);

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Mis compras</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

        <?php include 'navbar.php'; ?>

    </body>
</html>
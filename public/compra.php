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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mis compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<?php include 'navbar.php'; ?>

<header class="w-full bg-gradient-to-r from-blue-500 to-indigo-600">
    <div class="max-w-6xl mx-auto px-6 py-10 flex flex-col gap-4 text-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h1 class="text-3xl font-bold">
                Compra #<?php echo $saleData->getSaleId(); ?>
            </h1>
            <p class="text-sm sm:text-base opacity-90">
                <?php echo $saleData->getDate(); ?>
            </p>
        </div>

        <div class="bg-white/10 rounded-xl p-4 flex flex-col gap-2 backdrop-blur">
            <p class="text-sm sm:text-base">
                <span class="font-semibold">Dirección entrega:</span>
                <?php echo $saleData->getDelAddr() . " " . $saleData->getDelCiu() . " " . $saleData->getDelProv(); ?>
            </p>
            <p class="text-sm sm:text-base">
                <span class="font-semibold">Dirección facturación:</span>
                <?php echo $saleData->getFacAddr() . " " . $saleData->getFacCiu() . " " . $saleData->getFacProv(); ?>
            </p>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
    <div class="flex flex-col gap-4">
        <?php foreach ($saleProds as $prod): ?>

            <a
                    href="producto.php?id=<?php echo $prod->getProdId(); ?>"
                    class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:shadow-md hover:border-blue-200 transition"
            >
                <div class="flex flex-col gap-1">
                    <p class="text-lg font-semibold group-hover:text-blue-600 transition">
                        <?php echo $prod->getName(); ?>
                    </p>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium text-gray-600">Talla:</span>
                        <?php echo $prod->getSize(); ?>
                    </p>
                </div>

                <div class="text-sm text-gray-600 sm:text-right">
                    <span class="font-medium text-gray-700">Cantidad:</span>
                    <?php echo $prod->getQuant(); ?>
                </div>
            </a>

        <?php endforeach; ?>
    </div>
</main>

</body>
</html>

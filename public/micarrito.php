<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Services\CarritoService;
use Cdcrane\Dwes\Utils\AuthUtils;

session_start();

AuthUtils::checkLoginRedirectToLogin();

$cartEntries = CarritoService::getCartContents($_SESSION['cartId']);
$cartPrice = CarritoService::getCartTotal($_SESSION['cartId']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mi carrito</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 text-gray-900">

<?php include 'navbar.php'; ?>

<main class="max-w-6xl mx-auto px-4 md:px-10 py-12">

    <h1 class="text-4xl font-extrabold mb-10 text-center">Mi carrito</h1>

    <?php if(empty($cartEntries)): ?>
        <p class="text-center text-2xl text-gray-500 my-20">No tienes nada en tu carrito en este momento.</p>
    <?php else: ?>
        <div class="grid gap-6">
            <?php foreach($cartEntries as $entry): ?>
                <section class="bg-white rounded-xl shadow-md p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:shadow-xl transition-shadow">
                    <div class="flex items-center gap-4">
                        <a href="eliminar-de-carrito.php?prodID=<?php echo $entry->getProdId(); ?>&size=<?php echo $entry->getSize(); ?>" class="text-red-500 hover:text-red-700 transition-colors">
                            <svg viewBox="0 0 24 24" fill="none" class="w-7 h-7" title="Eliminar <?php echo $entry->getProdName() . ' en tamaño ' . $entry->getSize(); ?>">
                                <path d="M7.69231 8.70833H5V8.16667H9.84615M7.69231 8.70833V19H16.3077V8.70833M7.69231 8.70833H16.3077M16.3077 8.70833H19V8.16667H14.1538M9.84615 8.16667V6H14.1538V8.16667M9.84615 8.16667H14.1538" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 11V17" stroke="#000" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 11V17" stroke="#000" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 11V17" stroke="#000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <p class="font-medium text-lg">x<b><?php echo $entry->getQuantity(); ?></b></p>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <p class="font-semibold text-lg"><?php echo $entry->getProdName(); ?></p>
                        <p class="text-gray-500">Talla: <?php echo $entry->getSize(); ?></p>
                    </div>
                    <div class="text-right text-lg md:text-xl font-bold">€<?php echo $entry->getEntryTotal(); ?></div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<section class="max-w-6xl mx-auto px-4 md:px-10 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 flex flex-col items-end gap-4 md:gap-6">
        <p class="text-lg">Subtotal <b>€<?php echo $cartPrice - $cartPrice / 100 * 21; ?></b></p>
        <p class="text-lg">+ 21% IVA <b>€<?php echo $cartPrice / 100 * 21; ?></b></p>
        <p class="text-3xl font-extrabold">Total <b>€<?php echo $cartPrice; ?></b></p>
        <?php if($cartPrice > 0): ?>
            <a href="realizarcompra.php" class="mt-4 md:mt-6 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg transform transition-transform duration-300 hover:scale-105">
                Realizar compra
            </a>
        <?php endif; ?>
    </div>
</section>

</body>
</html>

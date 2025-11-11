<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Services\CarritoService;

session_start();

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

    <body>

        <?php include 'navbar.php'; ?>

        <main class="flex flex-col items-center justify-center px-[4%] md:px-[10%] lg:px-[25%] py-8">

            <?php foreach($cartEntries as $entry): ?>

                <section class="w-full p-6 border-b-2 border-gray-300/90 flex items-center justify-between">

                    <p>x<b><?php echo $entry->getQuantity(); ?></b></p>
                    <p><?php echo $entry->getProdName(); ?></p>
                    <p>Talla: <?php echo $entry->getSize(); ?></p>
                    <b>€<?php echo $entry->getEntryTotal(); ?></b>

                </section>

            <?php endforeach; ?>

        </main>

        <section class="w-full flex items-center justify-end px-[4%] md:px-[10%] lg:px-[25%]">

            <div class="flex flex-col items-end justify-center gap-6">

                <p class="text-lg">Subtotal <b>€<?php echo $cartPrice - $cartPrice / 100 * 21; ?></b></p>
                <p class="text-lg">+ 21% IVA <b>€<?php echo $cartPrice / 100 * 21; ?></b></p>
                <p class="text-3xl">Total <b>€<?php echo $cartPrice; ?></b></p>

            </div>

        </section>

    </body>
</html>
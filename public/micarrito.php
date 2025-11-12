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

                    <span class="flex items-center justify-center gap-4">
                        <a href="eliminar-de-carrito.php?prodID=<?php echo $entry->getProdId(); ?>&size=<?php echo $entry->getSize(); ?>">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline cursor-pointer" title="Eliminar <?php echo $entry->getProdName() . " en tamaño " . $entry->getSize(); ?>"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.69231 8.70833H5V8.16667H9.84615M7.69231 8.70833V19H16.3077V8.70833M7.69231 8.70833H16.3077M16.3077 8.70833H19V8.16667H14.1538M9.84615 8.16667V6H14.1538V8.16667M9.84615 8.16667H14.1538" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M10 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </a>
                        <p>x<b><?php echo $entry->getQuantity(); ?></b></p>
                    </span>
                    <p><?php echo $entry->getProdName(); ?></p>
                    <p>Talla: <?php echo $entry->getSize(); ?></p>
                    <b>€<?php echo $entry->getEntryTotal(); ?></b>

                </section>

            <?php endforeach; ?>

            <?php if(empty($cartEntries)): ?>

                <p class="text-2xl my-20">No tienes nada en tu carrito en este momento.</p>

            <?php endif; ?>

        </main>

        <section class="w-full flex items-center justify-end px-[4%] md:px-[10%] lg:px-[25%]">

            <div class="flex flex-col items-end justify-center gap-6">

                <p class="text-lg">Subtotal <b>€<?php echo $cartPrice - $cartPrice / 100 * 21; ?></b></p>
                <p class="text-lg">+ 21% IVA <b>€<?php echo $cartPrice / 100 * 21; ?></b></p>
                <p class="text-3xl">Total <b>€<?php echo $cartPrice; ?></b></p>

                <?php if($cartPrice > 0): ?>
                    <a href="realizarcompra.php" class="mt-8 px-6 py-2 bg-blue-500 transform transition-transform duration-300 hover:scale-110 cursor-pointer rounded-2xl font-bold text-white">Realizar compra</a>
                <?php endif; ?>
                
            </div>

        </section>

    </body>
</html>
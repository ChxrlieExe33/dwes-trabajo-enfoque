<?php 

require __DIR__ . '/../vendor/autoload.php';

session_start();

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\services\SaleService;

AuthUtils::checkLoginRedirectToLogin();

$myPurchases = SaleService::getSalesByCustomerId($_SESSION['user_id']);

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

        <main class="flex flex-col items-center justify-center px-[4%] md:px-[10%] lg:px-[25%] py-8">

            <?php foreach($myPurchases as $entry): ?>

                <section class="w-full p-6 border-b-2 border-gray-300/90 flex flex-col items-start gap-2 justify-between md:grid md:grid-cols-4">

                    <b>#<?php echo $entry->getId(); ?></b>
                    <p><?php echo $entry->getDate(); ?></p>
                    <p>Entrega: <?php echo $entry->getProvEntrega(); ?></p>
                    <b class="md:place-self-end text-blue-600">€<?php echo $entry->getTotal(); ?></b>

                </section>

            <?php endforeach; ?>

            <?php if(empty($myPurchases)): ?>

                <p class="text-2xl my-20">No has realizado ninguna compra todavía.</p>

            <?php endif; ?>

        </main>

    </body>
</html>
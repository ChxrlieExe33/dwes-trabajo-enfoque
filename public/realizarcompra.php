<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\requests\CompleteSaleRequest;
use Cdcrane\Dwes\Services\CarritoService;
use Cdcrane\Dwes\services\SaleService;
use Cdcrane\Dwes\Services\UserService;

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $saleInfo = new CompleteSaleRequest($_SESSION['user_id'], date('Y-m-d'), 
    $_POST['d_ent'], $_POST['c_ent'], $_POST['p_ent'],
    $_POST['d_fac'], $_POST['c_fac'], $_POST['p_fac']);

    SaleService::completeSale($saleInfo, $_SESSION['cartId']);

}

$cartPrice = CarritoService::getCartTotal($_SESSION['cartId']);

if ($cartPrice <= 0) {
    header("Location: micarrito.php");
}

$userData = UserService::getUserData($_SESSION['user_id']);

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Mi carrito</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

    <?php include_once 'navbar.php'; ?>

        <form class="flex flex-col items-center justify-items-start px-[4%] md:px-[25%] pt-8 pb-12 gap-8" method="post">

            <h1 class="text-2xl font-bold">Realizar compra</h1>

            <section class="w-full flex flex-col md:flex-row items-center justify-evenly gap-2">

                <p class="text-lg">Subtotal <b>€<?php echo $cartPrice - $cartPrice / 100 * 21; ?></b></p>
                <p class="text-lg">+ 21% IVA <b>€<?php echo $cartPrice / 100 * 21; ?></b></p>
                <p class="text-3xl">Total <b>€<?php echo $cartPrice; ?></b></p>

            </section>

            <span class="w-full h-1 bg-gray-400/60 rounded-2xl my-2"></span>

            <h1 class="text-2xl font-bold">Datos de entrega</h1>

            <section class="w-full flex flex-col items-start justify-items-start">

                <label for="d_entrega" class="font-bold">Dirección:</label>
                <input id="d_entrega" type="text" name="d_ent" value="<?php echo $userData->getDireccionEntrega(); ?>" placeholder="Direccion entrega..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

            </section>   

            <section class="w-full flex flex-col items-start justify-items-start">

                <label for="c_entrega" class="font-bold">Ciudad:</label>
                <input id="c_entrega" type="text" name="c_ent" value="<?php echo $userData->getCiudadEntrega(); ?>" placeholder="Ciudad entrega..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
            
            </section>   

            <section class="w-full flex flex-col items-start justify-items-start">

                <label for="p_entrega" class="font-bold">Provincia:</label>
                <input id="p_entrega" type="text" name="p_ent" value="<?php echo $userData->getProvinciaEntrega(); ?>" placeholder="Provincia entrega..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

            
            </section>

            <span class="w-full h-1 bg-gray-400/60 rounded-2xl my-2"></span>

            <h1 class="text-2xl font-bold">Datos de facturación</h1>

            <section class="w-full flex flex-col items-start justify-items-start">

                <label for="d_fac" class="font-bold">Dirección:</label>
                <input id="d_fac" type="text" name="d_fac" value="<?php echo $userData->getDireccionFacturacion(); ?>" placeholder="Direccion facturacion..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

            
            </section>   

            <section class="w-full flex flex-col items-start justify-items-start">

                <label for="c_fac" class="font-bold">Ciudad:</label>
                <input id="c_fac" type="text" name="c_fac" value="<?php echo $userData->getCiudadFacturacion(); ?>" placeholder="Ciudad facturacion..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

            
            </section>   

            <section class="w-full flex flex-col items-start justify-items-start">

                <label for="p_fac" class="font-bold">Provincia:</label>
                <input id="p_fac" type="text" name="p_fac" value="<?php echo $userData->getProvinciaFacturacion(); ?>" placeholder="Provincia facturacion..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
            
            </section>

            <span class="w-full h-1 bg-gray-400/60 rounded-2xl my-2"></span>

            <button type="submit" class="font-bold text-white bg-blue-500 text-xl rounded-2xl py-4 px-10 transform transition-transform duration-300 hover:scale-110 cursor-pointer">Confirmar compra</button>

        </main>

    </body>
</html>
<?php

    require __DIR__ . '/../vendor/autoload.php';

    use Cdcrane\Dwes\Services\ProductService;
    use Cdcrane\Dwes\Utils\AuthUtils;

    session_start();

    $products = ProductService::getNewestProductsHomePageView();

    $loggedIn = AuthUtils::isLoggedInAllowAll();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        
        <?php require "navbar.php"; ?>

        <main class="w-full min-h-screen flex items-center justify-center gap-4">

            <?php foreach($products as $product): ?>

                <a href="producto.php?id=<?php echo $product->getId(); ?>" class="w-[400px] h-[500px] bg-blue-100 rounded-xl shadow-lg flex flex-col gap-4 items-start justify-center py-6 px-4">

                    <img class="w-full h-[80%] mb-auto" src="/dwes-trabajo-enfoque/src/images/<?php echo $product->getNombreImagen(); ?>" alt="Imagen para producto <?php echo $product->getNombre(); ?>">

                    <h1 class="text-xl font-bold"><?php echo $product->getNombre(); ?></h1>
                    <p>€<?php echo $product->getPrecio(); ?></p>

                </a>

            <?php endforeach; ?>

        </main>

    </body>
</html>
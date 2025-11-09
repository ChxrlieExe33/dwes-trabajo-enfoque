<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Services\ProductService;

$products = ProductService::getAllProducts();

session_start();

?>

<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Productos Zapatoland</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>

    <?php include_once 'navbar.php';?>

    <main class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 px-6 md:px-12 py-8">

        <?php if(count($products) > 0): ?>

            <?php foreach($products as $product): ?>

                <a href="producto.php?id=<?php echo $product->getId(); ?>" class="bg-blue-100 rounded-xl shadow-lg flex flex-col gap-4 items-start justify-center py-6 px-4">

                    <img class="w-full h-[75%] mb-auto rounded-2xl" src="/dwes-trabajo-enfoque/src/images/<?php echo $product->getNombreImagen(); ?>" alt="Imagen para producto <?php echo $product->getNombre(); ?>">

                    <h1 class="text-xl font-bold"><?php echo $product->getNombre(); ?></h1>
                    <p>€<?php echo $product->getPrecio(); ?></p>

                </a>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>

    <?php if(count($products)<1): ?>

        <h1 class="text-2xl text-red-800 w-full text-center">No se pudo encontrar productos.</h1>

    <?php endif; ?>

</body>

</html>

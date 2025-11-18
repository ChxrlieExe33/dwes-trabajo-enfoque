<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Services\ProductService;

if (!isset($_GET['search'])) {
    $products = ProductService::getAllProducts();
} else {
    $products = ProductService::searchProductsByName($_GET['search']);
}

session_start();

?>

<!DOCTYPE html>
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

    <form class="w-full flex flex-col md:flex-row items-center justify-center py-4 md:py-12 gap-4" method="get">

        <input type="text" name="search" placeholder="Buscar producto..." class="px-6 py-2 rounded-2xl border-1 border-gray-300/90 shadow-xl min-w-[85%] md:min-w-[40%]">

        <button type="submit" class="bg-blue-400 text-white font-bold px-6 py-2 rounded-2xl shadow-xl transform transition-transform duration-300 hover:scale-110 cursor-pointer">Buscar</button>

    </form>

    <?php if(isset($_GET['search'])): ?>

        <p class="text-xl w-full text-center">Resultados de la búsqueda: <b><?php echo $_GET['search']; ?></b></p>

    <?php endif; ?>

    <main class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 px-6 md:px-6 py-4 md:py-8">

        <?php if(count($products) > 0): ?>

            <?php foreach($products as $product): ?>

                <a href="producto.php?id=<?php echo $product->getId(); ?>" class="border-1 border-gray-200/80 rounded-xl shadow-xl flex flex-col gap-4 items-start justify-center py-6 px-4">

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

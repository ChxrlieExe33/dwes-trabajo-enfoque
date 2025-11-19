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
        <title>Zapatoland home</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="pb-12">
        
        <?php require "navbar.php"; ?>

        <header class="w-full min-h-[400px] bg-gradient-to-b from-blue-600 via-blue-500 to-blue-600 flex flex-col items-center justify-center gap-8 py-8 md:py-2 shadow-xl">

            <h1 class="text-2xl md:text-4xl font-bold text-white">¡Bienvenido a Zapatoland!</h1>

            <p class="text-lg md:text-xl text-white max-w-[90%] lg:max-w-[45%] text-center">
                En Zapatoland vivimos y respiramos deporte. Somos el destino definitivo para los amantes del movimiento, la velocidad y el estilo. Aquí encontrarás las últimas colecciones de zapatillas deportivas de las mejores marcas y diseños que marcan tendencia.<br><br>

                Ya sea que corras, entrenes, juegues o simplemente busques comodidad para tu día a día, en Zapatoland tenemos el par perfecto para ti.
                Rinde al máximo, luce increíble y siente la diferencia en cada paso.
            </p>

        </header>

        <h1 class="text-2xl font-bold w-full text-center py-2 md:py-8">Productos recomendados</h1>

        <main class="w-full flex flex-col md:flex-row items-center justify-center gap-4 py-4">

            <?php foreach($products as $product): ?>

                <a href="producto.php?id=<?php echo $product->getId(); ?>" class="w-[90%] h-[90%] md:w-[400px] md:h-[500px] border-1 border-gray-200/80 rounded-xl shadow-xl flex flex-col gap-4 items-start justify-center py-6 px-4">

                    <img class="w-full h-[80%] mb-auto" src="images/<?php echo $product->getNombreImagen(); ?>" alt="Imagen para producto <?php echo $product->getNombre(); ?>">

                    <h1 class="text-xl font-bold"><?php echo $product->getNombre(); ?></h1>
                    <p>€<?php echo $product->getPrecio(); ?></p>

                </a>

            <?php endforeach; ?>

        </main>

    </body>
</html>
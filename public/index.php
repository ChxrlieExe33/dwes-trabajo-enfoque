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
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Modern font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>

<body class="bg-gray-50 pb-16">

<?php require "navbar.php"; ?>


    <header class="w-full min-h-[420px] bg-gradient-to-br from-blue-600 via-blue-500 to-blue-600 flex flex-col items-center justify-center text-center px-6 py-16 shadow-lg">

        <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow-lg">
            ¡Bienvenido a Zapatoland!
        </h1>

        <p class="mt-6 text-white text-lg md:text-xl max-w-3xl leading-relaxed opacity-95">
            En Zapatoland vivimos y respiramos deporte. Somos el destino definitivo para los amantes del movimiento, la velocidad y el estilo. Aquí encontrarás las últimas colecciones de zapatillas deportivas de las mejores marcas y diseños que marcan tendencia.
            <br><br>
            Ya sea que corras, entrenes, juegues o simplemente busques comodidad para tu día a día, en Zapatoland tenemos el par perfecto para ti.
            Rinde al máximo, luce increíble y siente la diferencia en cada paso.
        </p>

    </header>


    <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800 py-10">
        Productos recomendados
    </h1>


    <main class="w-full px-6 flex flex-col items-center justify-center md:flex-row gap-8">

        <?php foreach($products as $product): ?>

            <a href="producto.php?id=<?php echo $product->getId(); ?>"
               class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow p-4 flex flex-col">

                <div class="w-full h-64 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img
                            src="images/<?php echo $product->getNombreImagen(); ?>"
                            alt="Imagen para producto <?php echo $product->getNombre(); ?>"
                            class="object-cover w-full h-full"
                    >
                </div>

                <h1 class="mt-4 text-xl font-semibold text-gray-900">
                    <?php echo $product->getNombre(); ?>
                </h1>

                <p class="text-lg font-medium text-blue-700">
                    €<?php echo $product->getPrecio(); ?>
                </p>

            </a>

        <?php endforeach; ?>

    </main>

</body>
</html>

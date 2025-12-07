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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body class="bg-gray-50">

<?php include_once 'navbar.php'; ?>

<!-- Search section -->
<section class="w-full py-8 md:py-12 flex flex-col items-center">
    <form method="get" class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto px-4">

        <input
                type="text"
                name="search"
                placeholder="Buscar producto..."
                class="w-full md:w-96 px-5 py-3 rounded-2xl border border-gray-300 bg-white shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none text-gray-700"
        >

        <button
                type="submit"
                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-2xl shadow-md hover:bg-blue-700 transition-all"
        >
            Buscar
        </button>

    </form>

    <?php if(isset($_GET['search'])): ?>
        <p class="text-lg mt-6 text-gray-700">
            Resultados de la búsqueda:
            <b class="text-blue-700"><?php echo htmlspecialchars($_GET['search']); ?></b>
        </p>
    <?php endif; ?>
</section>

<!-- Products grid -->
<main class="w-full px-6 pb-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

    <?php if(count($products) > 0): ?>
        <?php foreach($products as $product): ?>

            <a href="producto.php?id=<?php echo $product->getId(); ?>"
               class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow p-4 flex flex-col">

                <div class="w-full h-[300px] rounded-xl overflow-hidden flex items-center justify-center bg-gray-100">
                    <img
                            class="object-cover w-full h-full"
                            src="images/<?php echo $product->getNombreImagen(); ?>"
                            alt="Imagen para producto <?php echo $product->getNombre(); ?>"
                    >
                </div>

                <h1 class="mt-4 text-xl font-semibold text-gray-900">
                    <?php echo $product->getNombre(); ?>
                </h1>

                <p class="mt-1 text-lg font-medium text-blue-700">
                    €<?php echo $product->getPrecio(); ?>
                </p>

            </a>

        <?php endforeach; ?>
    <?php endif; ?>

</main>

<?php if(count($products) < 1): ?>
    <h1 class="text-2xl text-red-700 w-full text-center pb-16">
        No se pudo encontrar productos.
    </h1>
<?php endif; ?>

</body>
</html>

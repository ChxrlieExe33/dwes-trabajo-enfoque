<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\requests\SaveNewProductRequest;
use Cdcrane\Dwes\Services\ProductService;

session_start();

AuthUtils::restrictPageAdminOnly();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $productRequest = new SaveNewProductRequest($_POST['name'], $_POST['description'], $_POST['price'], $_POST['colour'], $_POST['factory'], $_FILES);

    $prodId = ProductService::insertNewProduct($productRequest);

    header("Location: producto.php?id=$prodId");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Nuevo producto</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

    <?php include_once 'navbar.php' ?>

        <form class="w-full px-[4%] md:px-[10%] lg:px-[30%] flex flex-col items-center justify-items-start py-8 gap-4" method='post' enctype='multipart/form-data'>

            <h1 class="text-2xl font-bold mb-4">Nuevo producto</h1>
        
            <input type="text" name="name" class="w-full px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md" placeholder="Nombre del producto">

            <input type="text" name="factory" class="w-full px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md" placeholder="Nombre del fabricante">

            <textarea type="text" name="description" class="w-full px-6 py-2 rounded-xl border-1 border-gray-300/80 min-h-[200px] shadow-gray-300/60 shadow-md" placeholder="Descripción"></textarea>

            <span class="w-full flex flex-col md:flex-row items-center justify-between gap-4">

                <input type="number" name="price" class="px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md w-full md:min-w-[45%]" placeholder="Precio">

                <input type="text" name="colour" class="px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md w-full md:min-w-[45%]" placeholder="Color">

            </span>

            <input type="file" multiple name="file[]" id="file" class="w-full border-1 border-gray-300/80 shadow-gray-300/60 shadow-md p-8 rounded-xl">

            <button type="submit" class="px-6 py-2 rounded-3xl bg-blue-700 text-white font-bold">Submit</button>

        </form>
    
    </body>
</html>
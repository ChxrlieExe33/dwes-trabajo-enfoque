<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\requests\SaveNewProductRequest;
use Cdcrane\Dwes\Services\ProductService;

session_start();

AuthUtils::restrictPageAdminOnly();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $productRequest = new SaveNewProductRequest(
        htmlspecialchars($_POST['name']),
        htmlspecialchars($_POST['description'] ?? ''),
        htmlspecialchars($_POST['price']),
        htmlspecialchars($_POST['colour']),
        htmlspecialchars($_POST['factory']),
        $_FILES
    );

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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200">

<?php include_once 'navbar.php' ?>

<div class="w-full px-4 md:px-10 lg:px-[25%] py-10">
    <form
            class="bg-white/80 backdrop-blur-md shadow-xl rounded-3xl p-8 sm:p-10 flex flex-col gap-6 border border-gray-200"
            method="post"
            enctype="multipart/form-data"
    >

        <h1 class="text-3xl font-extrabold text-gray-800 text-center mb-4">
            Nuevo producto
        </h1>

        <input
                type="text"
                name="name"
                class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-blue-300/40 transition-all shadow-sm"
                placeholder="Nombre del producto"
        >

        <input
                type="text"
                name="factory"
                class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-blue-300/40 transition-all shadow-sm"
                placeholder="Nombre del fabricante"
        >

        <textarea
                name="description"
                class="w-full px-5 py-3 rounded-xl border border-gray-300 min-h-[180px] focus:ring-4 focus:ring-blue-300/40 transition-all shadow-sm"
                placeholder="Descripción"
        ></textarea>

        <div class="flex flex-col md:flex-row gap-5">

            <input
                    type="number"
                    name="price"
                    class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-blue-300/40 transition-all shadow-sm"
                    placeholder="Precio"
            >

            <input
                    type="text"
                    name="colour"
                    class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-blue-300/40 transition-all shadow-sm"
                    placeholder="Color"
            >

        </div>

        <input
                type="file"
                multiple
                name="file[]"
                id="file"
                class="w-full border border-gray-300 shadow-sm p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition"
        >

        <button
                type="submit"
                class="w-full py-3 rounded-2xl bg-blue-600 text-white text-lg font-semibold shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-300"
        >
            Crear producto
        </button>

    </form>
</div>

</body>
</html>

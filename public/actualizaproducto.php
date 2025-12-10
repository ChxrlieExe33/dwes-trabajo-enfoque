<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Requests\UpdateProductDataRequest;
use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\ProductService;

session_start();

AuthUtils::restrictPageAdminOnly();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = new UpdateProductDataRequest(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['description'] ?? ''), 
                                        htmlspecialchars($_POST['price']), htmlspecialchars($_POST['colour']), 
                                        htmlspecialchars($_POST['factory']));

    ProductService::updateExistingProduct($data, $_POST['id']);

    header("Location: producto.php?id=" . $_POST['id']);

}

if (!isset($_GET['id'])) {
    $error = 'No has proporcionado un ID de producto';
}

$product = ProductService::getProductDetail($_GET['id']);

if ($product == null) {
    $error = 'No existe el producto con ID ' . $_GET["id"];
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Actualizar producto ID <?php echo $_GET['id'] ?? ''; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

        <?php include_once 'navbar.php'; ?>

        <form class="w-full px-[4%] md:px-[10%] lg:px-[30%] flex flex-col items-center justify-items-start py-8 gap-4" method='post' enctype='multipart/form-data'>

            <?php if (isset($_GET['msg'])): ?>
                <p class="text-xl text-green-600 font-bold"><?php echo htmlspecialchars($_GET['msg']); ?></p>
            <?php endif; ?>

            <h1 class="text-2xl font-bold mb-4">Actualizar producto con ID <?php echo $_GET['id']; ?></h1>

            <!-- Campo invisible para enviar el ID para delante con el formulario, ya que se pierde el $_GET['id'] -->
            <input class="hidden" name="id" type="text" value="<?php echo $_GET['id']; ?>">
        
            <input required type="text" name="name" value="<?php echo $product->getNombre(); ?>" class="w-full px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md" placeholder="Nombre del producto">

            <input required type="text" name="factory" value="<?php echo $product->getFabricante(); ?>" class="w-full px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md" placeholder="Nombre del fabricante">

            <textarea type="text" name="description" class="w-full px-6 py-2 rounded-xl border-1 border-gray-300/80 min-h-[200px] shadow-gray-300/60 shadow-md" placeholder="Descripción"><?php echo $product->getDescripcion(); ?></textarea>

            <span class="w-full flex flex-col md:flex-row items-center justify-between gap-4">

                <input required type="number" name="price" value="<?php echo $product->getPrecio(); ?>" class="px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md w-full md:min-w-[45%]" placeholder="Precio">

                <input required type="text" name="colour" value="<?php echo $product->getColor(); ?>" class="px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md w-full md:min-w-[45%]" placeholder="Color">

            </span>

            <button type="submit" class="px-6 py-2 rounded-3xl bg-blue-700 text-white font-bold transform transition-transform duration-300 hover:scale-110 cursor-pointer">Actualiza</button>

        </form>

        <form class="w-full px-[4%] md:px-[10%] lg:px-[30%] flex flex-col items-center justify-items-start py-8 gap-4" method='get' action="guardar-stock.php">

            <div class="w-full h-2 bg-blue-500/50 rounded-2xl"></div>

            <h1 class="text-2xl font-bold mb-4">Añadir stock de este producto</h1>

            <span class="w-full flex flex-col md:flex-row items-center justify-between gap-4">

                <input type="number" name="prodId" class="hidden" value="<?php echo $_GET['id']; ?>">

                <input required type="number" name="size"  class="px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md w-full md:min-w-[45%]" placeholder="Talla">

                <input required type="number" name="count" class="px-6 py-2 rounded-xl border-1 border-gray-300/80 shadow-gray-300/60 shadow-md w-full md:min-w-[45%]" placeholder="Cantidad">

            </span>

            <button type="submit" class="px-6 py-2 rounded-3xl bg-blue-700 text-white font-bold transform transition-transform duration-300 hover:scale-110 cursor-pointer">Añadir</button>

        </form>


        <section class="w-full px-[4%] md:px-[10%] lg:px-[30%] flex flex-col items-center justify-items-start py-8 gap-4">

            <h1 class="text-2xl text-red-800 mt-8">Zona de peligro</h1>

            <div class="w-full h-2 bg-red-800/50 rounded-2xl"></div>

            <a href="eliminar-producto.php?id=<?php echo $product->getId(); ?>" class="px-6 py-2 rounded-2xl font-bold bg-red-800 text-white transform transition-transform duration-300 hover:scale-110 cursor-pointer">Eliminar producto</a>

        </section>

    </body>
</html>
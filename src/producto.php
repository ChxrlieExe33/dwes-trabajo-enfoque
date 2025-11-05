<?php

    require 'services/ProductService.php';

    if(!isset($_GET["id"])){
        header("location: index.php");
    }

    $id = $_GET['id'];

    $productData = ProductService::getProductDetail($id);
    $productImages = ProductService::getProductImages($id);

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

    <div class="w-full px-[15%]">

        <header class="w-full h-[350px] bg-blue-50 flex items-center justify-evenly border-x-1">

            <?php foreach ($productImages as $image): ?>

                <img src="images/<?php echo $image; ?>" alt="<?php echo $image; ?>" class="w-[300px] h-[300px] rounded-xl">

            <?php endforeach; ?>

        </header>

        <main class="w-full flex flex-col items-center justify-start pt-8 gap-6 border-x-1 min-h-screen">

            <span class="w-full flex items-center justify-evenly">

                <h1 class="text-3xl font-bold"><?php echo $productData->getNombre(); ?></h1>

                <p class="text-xl"><?php echo $productData->getPrecio(); ?>€</p>

            </span>



            <p class="text-xl"><?php echo $productData->getDescripcion(); ?></p>

        </main>

    </div>

</body>

</html>

<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;

session_start();

AuthUtils::restrictPageAdminOnly();


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Panel administración</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

        <?php include_once "navbar.php"; ?>

        <h1 class="text-2xl p-4 w-full text-center">Hola <b><?php echo $_SESSION['email']; ?></b></h1>

        <main class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-8">

            <a class="border-2 border-gray-300/80 shadow-lg p-8 rounded-lg flex flex-col items-center justify-center hover:bg-gray-200/50" href="crearproducto.php">

                <h1 class="text-lg font-bold">Crear producto</h1>

                <p>Añadir un nuevo producto a la tienda.</p>

            </a>

            <a class="border-2 border-gray-300/80 shadow-lg p-8 rounded-lg flex flex-col items-center justify-center hover:bg-gray-200/50" href="compras.php">

                <h1 class="text-lg font-bold">Historico compras</h1>

                <p>Ver todas las compras realizadas.</p>

            </a>

            <a class="border-2 border-gray-300/80 shadow-lg p-8 rounded-lg flex flex-col items-center justify-center hover:bg-gray-200/50" href="index.php">

                <h1 class="text-lg font-bold">Usuarios</h1>

                <p>Ver todos los usuarios.</p>

            </a>

        </main>

    </body>
</html>
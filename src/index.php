<?php

    require_once "utils/AuthUtils.php";

    session_start();

    $loggedIn = AuthUtils::isLoggedInAllowAccess();

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
        
        <nav class="w-full h-16 flex items-center justify-between px-8 bg-slate-800 text-white">

            <a class="text-2xl font-bold cursor-pointer hover:text-gray-400" href="index.php">Zapatoland</a>

            <span class="hidden md:flex items-center-safe justify evenly gap-4 h-full [&>a]:hover:border-b-2 [&>a]:hover:border-gray-400 [&>a]:hover:text-gray-400 [&>a]:cursor-pointer [&>a]:h-full [&>a]:content-center">

                <a>Productos</a>

                <?php if ($loggedIn): ?>

                    <a>Mi cuenta</a>
                    <a>Mis compras</a>
                    <a>Carrito</a>
                    <a class="text-red-800" href="./logout.php">Log out</a>

                <?php endif; ?>

                <?php if(!$loggedIn): ?>

                    <a class="text-blue-300 font-bold" href="login.php">Log in</a>

                <?php endif; ?>

            </span>

        </nav>

    </body>
</html>
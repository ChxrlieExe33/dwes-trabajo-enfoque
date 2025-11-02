<?php

    require_once "utils/AuthUtils.php";

    session_start();

    $loggedIn = AuthUtils::isLoggedInAllowAll();

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
        
        <?php require "navbar.php"; ?>

    </body>
</html>
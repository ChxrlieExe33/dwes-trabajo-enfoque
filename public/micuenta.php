<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Services\UserService;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
}

$userData = UserService::getUserData($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mi cuenta</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

    <?php include_once 'navbar.php'; ?>

</body>

</html>

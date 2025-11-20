<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\UserService;

session_start();

AuthUtils::restrictPageAdminOnly();

if (!isset($_GET['userId'])) {
    die("Debes proporcionar un ID de usuario");
}

UserService::deleteUser($_GET['userId']);

if ($_SESSION['user_id'] == $_GET['userId']) {
    header("Location: logout.php");
} else {
    header("Location: usuarios.php");
}
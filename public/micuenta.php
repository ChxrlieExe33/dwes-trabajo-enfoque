<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Models\UserProfile;
use Cdcrane\Dwes\Services\UserService;
use Cdcrane\Dwes\Utils\AuthUtils;

session_start();

AuthUtils::checkLoginRedirectToLogin();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $updateData = new UserProfile(
        $_SESSION['user_id'],
        $_POST['nombre'],
        $_POST['apellidos'],
        $_SESSION['email'],
        $_POST['fecha_nac'],
        $_POST['direccion_entrega'],
        $_POST['ciudad_entrega'],
        $_POST['provincia_entrega'],
        $_POST['direccion_facturacion'],
        $_POST['ciudad_facturacion'],
        $_POST['provincia_facturacion']
    );

    UserService::updateUserData($updateData);
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 text-slate-800">

<?php include_once 'navbar.php'; ?>

<form class="w-full px-4 md:px-[25%] py-10 flex flex-col items-center gap-6 pb-20" method="post">

    <h1 class="text-3xl font-bold">Datos personales</h1>

    <p class="text-lg"><b>Correo:</b> <?php echo $userData->getEmail(); ?></p>

    <!-- Nombre -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="nombre" class="font-semibold">Nombre:</label>
        <input
                id="nombre"
                type="text"
                name="nombre"
                value="<?php echo $userData->getNombre(); ?>"
                placeholder="Tu nombre..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <!-- Apellidos -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="apellidos" class="font-semibold">Apellidos:</label>
        <input
                id="apellidos"
                type="text"
                name="apellidos"
                value="<?php echo $userData->getApellidos(); ?>"
                placeholder="Tus apellidos..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <!-- Fecha nacimiento -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="fecha_nac" class="font-semibold">Fecha nacimiento:</label>
        <input
                id="fecha_nac"
                type="date"
                name="fecha_nac"
                value="<?php echo $userData->getFechaNacimiento(); ?>"
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <span class="w-full h-[2px] bg-gray-300 rounded my-4"></span>

    <h1 class="text-3xl font-bold">Datos de entrega</h1>

    <!-- Dirección entrega -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="d_entrega" class="font-semibold">Dirección:</label>
        <input
                id="d_entrega"
                type="text"
                name="direccion_entrega"
                value="<?php echo $userData->getDireccionEntrega(); ?>"
                placeholder="Dirección entrega..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <!-- Ciudad entrega -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="c_entrega" class="font-semibold">Ciudad:</label>
        <input
                id="c_entrega"
                type="text"
                name="ciudad_entrega"
                value="<?php echo $userData->getCiudadEntrega(); ?>"
                placeholder="Ciudad entrega..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <!-- Provincia entrega -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="p_entrega" class="font-semibold">Provincia:</label>
        <input
                id="p_entrega"
                type="text"
                name="provincia_entrega"
                value="<?php echo $userData->getProvinciaEntrega(); ?>"
                placeholder="Provincia entrega..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <span class="w-full h-[2px] bg-gray-300 rounded my-4"></span>

    <h1 class="text-3xl font-bold">Datos de facturación</h1>

    <!-- Dirección facturación -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="d_fac" class="font-semibold">Dirección:</label>
        <input
                id="d_fac"
                type="text"
                name="direccion_facturacion"
                value="<?php echo $userData->getDireccionFacturacion(); ?>"
                placeholder="Dirección facturación..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <!-- Ciudad facturación -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="c_fac" class="font-semibold">Ciudad:</label>
        <input
                id="c_fac"
                type="text"
                name="ciudad_facturacion"
                value="<?php echo $userData->getCiudadFacturacion(); ?>"
                placeholder="Ciudad facturación..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <!-- Provincia facturación -->
    <section class="px-4 md:px-20 w-full flex flex-col gap-1">
        <label for="p_fac" class="font-semibold">Provincia:</label>
        <input
                id="p_fac"
                type="text"
                name="provincia_facturacion"
                value="<?php echo $userData->getProvinciaFacturacion(); ?>"
                placeholder="Provincia facturación..."
                class="w-full px-6 py-2 border border-gray-300 rounded-2xl shadow-md focus:ring-2 focus:ring-slate-400"
        >
    </section>

    <button
            type="submit"
            class="px-8 py-3 mt-4 rounded-2xl bg-slate-800 text-white font-bold text-lg shadow-lg hover:scale-[1.05] transition-transform duration-300">
        Actualizar
    </button>

</form>

</body>

</html>

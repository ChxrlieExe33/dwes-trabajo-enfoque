<?php

require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Models\UserProfile;
use Cdcrane\Dwes\Services\UserService;
use Cdcrane\Dwes\Utils\AuthUtils;

session_start();

AuthUtils::checkLoginRedirectToLogin();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $updateData = new UserProfile($_SESSION['user_id'], $_POST['nombre'], $_POST['apellidos'], $_SESSION['email'], $_POST['fecha_nac'], 
    $_POST['direccion_entrega'], $_POST['ciudad_entrega'], $_POST['provincia_entrega'], $_POST['direccion_facturacion'], $_POST['ciudad_facturacion'], $_POST['provincia_facturacion']);

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
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

    <?php include_once 'navbar.php'; ?>

    <form class="w-full px-4 md:px-[25%] py-6 flex flex-col items-center justify-start gap-4 pb-12" method="post">

        <h1 class="text-2xl font-bold">Datos personales</h1>

        <p><b>Correo:</b> <?php echo $userData->getEmail(); ?></p>

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="nombre" class="font-bold">Nombre:</label>
            <input id="nombre" type="text" name="nombre" value="<?php echo $userData->getNombre(); ?>" placeholder="Tu nombre..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        </section>

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="apellidos" class="font-bold">Apellidos:</label>
            <input id="apellidos" type="text" name="apellidos" value="<?php echo $userData->getApellidos(); ?>" placeholder="Tus apellidos..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
        
        </section>

        <section class="px-4 md:px-20 w-full flex flex-col items-center justify-items-start">

            <label for="fecha_nac" class="font-bold">Fecha nacimiento:</label>
            <input id="fecha_nac" type="date" name="fecha_nac" value="<?php echo $userData->getFechaNacimiento(); ?>" class="px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        </section>

        <span class="w-full h-1 bg-gray-400/60 rounded-2xl my-4"></span>

        <h1 class="text-2xl font-bold">Datos de entrega</h1>

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="d_entrega" class="font-bold">Dirección:</label>
            <input id="d_entrega" type="text" name="direccion_entrega" value="<?php echo $userData->getDireccionEntrega(); ?>" placeholder="Direccion entrega..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        </section>   

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="c_entrega" class="font-bold">Ciudad:</label>
            <input id="c_entrega" type="text" name="ciudad_entrega" value="<?php echo $userData->getCiudadEntrega(); ?>" placeholder="Ciudad entrega..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
        
        </section>   

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="p_entrega" class="font-bold">Provincia:</label>
            <input id="p_entrega" type="text" name="provincia_entrega" value="<?php echo $userData->getProvinciaEntrega(); ?>" placeholder="Provincia entrega..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        
        </section>

        <span class="w-full h-1 bg-gray-400/60 rounded-2xl my-4"></span>

        <h1 class="text-2xl font-bold">Datos de facturación</h1>

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="d_fac" class="font-bold">Dirección:</label>
            <input id="d_fac" type="text" name="direccion_facturacion" value="<?php echo $userData->getDireccionFacturacion(); ?>" placeholder="Direccion facturacion..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        
        </section>   

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="c_fac" class="font-bold">Ciudad:</label>
            <input id="c_fac" type="text" name="ciudad_facturacion" value="<?php echo $userData->getCiudadFacturacion(); ?>" placeholder="Ciudad facturacion..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        
        </section>   

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="p_fac" class="font-bold">Provincia:</label>
            <input id="p_fac" type="text" name="provincia_facturacion" value="<?php echo $userData->getProvinciaFacturacion(); ?>" placeholder="Provincia facturacion..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
        
        </section>

        <button type="submit" class="px-6 py-2 rounded-2xl bg-slate-800 font-bold text-white shadow-xl transform transition-transform duration-300 hover:scale-110 cursor-pointer">Actualizar</button>

    </form>

</body>

</html>

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Models\UserPersonalDataAndRole;
use Cdcrane\Dwes\Services\UserService;
use Cdcrane\Dwes\Utils\AuthUtils;

session_start();

AuthUtils::restrictPageAdminOnly();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $updateData = new UserPersonalDataAndRole($_POST['id'], $_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['admin'] ?? false);

    UserService::updateUserInfoAdmin($updateData);

    header("Location: usuarios.php");

}

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
}

$userId = $_GET['id'];

$userData = UserService::getUserDataForAdmin($userId);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Gestionar usuarios</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

        <?php include_once 'navbar.php'; ?>

        <form class="w-full px-4 md:px-[25%] py-6 flex flex-col items-center justify-start gap-4 pb-12" method="post">

        <h1 class="text-2xl font-bold">Datos personales</h1>

        <input name="id" class="hidden" value="<?php echo $userData->getId(); ?>">

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="nombre" class="font-bold">Nombre:</label>
            <input id="nombre" type="text" name="nombre" value="<?php echo $userData->getNombre(); ?>" placeholder="Nombre..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">

        </section>

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="apellidos" class="font-bold">Apellidos:</label>
            <input id="apellidos" type="text" name="apellidos" value="<?php echo $userData->getApellidos(); ?>" placeholder="Apellidos..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
        
        </section>

        <section class="px-4 md:px-20 w-full flex flex-col items-start justify-items-start">

            <label for="email" class="font-bold">Email:</label>
            <input id="email" type="text" name="email" value="<?php echo $userData->getEmail(); ?>" placeholder="Email..." class="w-full px-6 py-2 border-1 border-gray-300/90 rounded-2xl shadow-lg">
        
        </section>

        <section class="px-4 md:px-20 w-full flex items-center justify-center gap-4">

            <label for="isadmin" class="font-bold">¿Es administrador?</label>
            <input type="checkbox" id="isadmin" name="admin" <?php if ($userData->isAdmin()): echo "checked"; endif; ?> class="w-8 h-8">

        </section>

        <button type="submit" class="px-6 py-2 rounded-2xl bg-blue-500 font-bold text-white shadow-xl transform transition-transform duration-300 hover:scale-110 cursor-pointer">Actualizar</button>


        <h1 class="text-2xl text-red-800 mt-8">Zona de peligro</h1>

        <div class="w-full h-2 bg-red-800/50 rounded-2xl"></div>

        <a href="eliminar-usuario.php?userId=<?php echo $userData->getId(); ?>" class="px-6 py-2 rounded-2xl font-bold bg-red-800 text-white transform transition-transform duration-300 hover:scale-110 cursor-pointer">Eliminar</a>

    </form>

    </body>
</html>
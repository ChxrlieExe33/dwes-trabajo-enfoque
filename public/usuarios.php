<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\UserService;

session_start();

AuthUtils::restrictPageAdminOnly();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['pass'] != $_POST['confirmPass']) {
        $passDontMatch = true;
    }

    UserService::registerUserAdminPanel($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['pass'], $_POST['admin'] ?? false);

}

$page = $_GET['page'] ?? 0;

if ($page < 0) {
    header("Location: compras.php?page=0");
}

$users = UserService::getUserListPaginated($page);

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

        <?php include 'navbar.php'; ?>

        <main class="flex flex-col lg:flex-row items-center lg:items-start justify-between w-full px-[4%]">

            <section class="flex flex-col items-center justify-center py-8 w-full lg:w-[45%]">

                <h1 class="text-2xl font-bold mb-4">Usuarios existentes</h1>

                <?php foreach($users as $entry): ?>

                    <a href="usuario.php?id=<?php echo $entry->getId(); ?>" class="w-full p-6 border-b-2 border-gray-300/90 flex items-center justify-between">

                        <p>ID: <?php echo $entry->getId(); ?></p>
                        <p><?php echo $entry->getName() . ' ' . $entry->getSurname(); ?></p>
                        
                        <p><?php echo $entry->getEmail(); ?></p>
                        <b><?php echo $entry->isAdmin() ? 'ADMIN' : 'CLIENTE'; ?></b>

                    </a>

                <?php endforeach; ?>

                <?php if(empty($users)): ?>

                    <p class="text-2xl my-20">No hay usuarios.</p>

                <?php endif; ?>

                <span class="w-[80%] md:w-[45%] flex items-center justify-evenly py-2 px-6 rounded-2xl bg-gray-200/70 border-1 border-gray-400/80 my-6 shadow-lg">

                    <?php if($page != 0): ?>
                        <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg transform transition-transform duration-300 hover:scale-110 cursor-pointer" href="usuarios.php?page=<?php echo $page - 1; ?>"><</a>
                    <?php else: ?>
                        <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg invisible" href="usuarios.php?page=<?php echo $page - 1; ?>"><</a>
                    <?php endif; ?>
                        
                    <p>Página <?php echo $page; ?></p>

                    <?php if(!empty($users)): ?>
                        <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg transform transition-transform duration-300 hover:scale-110 cursor-pointer" href="usuarios.php?page=<?php echo $page + 1; ?>">></a>
                    <?php else: ?>
                        <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg invisible" href="usuarios.php?page=<?php echo $page + 1; ?>">></a>
                    <?php endif; ?>

                </span>

            </section>

            <form class="flex flex-col items-center justify-items-start py-8 w-full lg:w-[45%] gap-4" method="post">

                <h1 class="text-2xl font-bold mb-4">Crear nuevo usuario</h1>

                <span class="w-full flex items-center justify-between">

                    <input type="text" name="name" class="px-6 py-2 border-1 border-gray-300/80 shadow-gray-300/50 shadow-lg rounded-2xl w-[48%]" placeholder="Nombre">
                    <input type="text" name="surname" class="px-6 py-2 border-1 border-gray-300/80 shadow-gray-300/50 shadow-lg rounded-2xl w-[48%]" placeholder="Apellidos">

                </span>

                <input type="text" name="email" class="px-6 py-2 border-1 border-gray-300/80 shadow-gray-300/50 shadow-lg rounded-2xl w-full" placeholder="Email">

                <?php if(isset($passDontMatch)): ?>
                    <p class="text-lg text-red-800">Las contraseñas no son iguales.</p>
                <?php endif; ?>

                <span class="w-full flex items-center justify-between">

                    <input type="password" name="pass" class="px-6 py-2 border-1 border-gray-300/80 shadow-gray-300/50 shadow-lg rounded-2xl w-[48%]" placeholder="Contraseña">
                    <input type="password" name="confirmPass" class="px-6 py-2 border-1 border-gray-300/80 shadow-gray-300/50 shadow-lg rounded-2xl w-[48%]" placeholder="Confirmar contraseña">

                </span>

                <span class="w-full flex justify-items-start gap-2 px-8">

                    <input type="checkbox" id="isadmin" name="admin" class="w-8 h-8">

                    <label for="isadmin" class="text-lg">¿Usuario administrador?</label>

                </span>

                <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-bold rounded-3xl mt-4 transform transition-transform duration-300 hover:scale-110 cursor-pointer">Crear</button>

            </form>

        </main>
        
        

    </body>
</html>
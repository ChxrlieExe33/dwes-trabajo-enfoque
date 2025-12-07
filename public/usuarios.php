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

    UserService::registerUserAdminPanel(
        htmlspecialchars($_POST['name']),
        htmlspecialchars($_POST['surname']),
        htmlspecialchars($_POST['email']),
        $_POST['pass'],
        $_POST['admin'] ?? false
    );
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200">

<?php include 'navbar.php'; ?>

<main class="flex flex-col lg:flex-row items-start justify-between w-full px-[4%] py-10 gap-10">

    <!-- USER LIST -->
    <section class="flex flex-col items-center w-full lg:w-[45%] gap-2">

        <h1 class="text-3xl font-extrabold mb-6 text-gray-800">Usuarios existentes</h1>

        <?php foreach($users as $entry): ?>

            <a href="usuario.php?id=<?php echo $entry->getId(); ?>"
               class="w-full bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-md border border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all">

                <p class="font-semibold text-gray-700">ID: <?php echo $entry->getId(); ?></p>

                <p class="text-gray-600">
                    <?php echo $entry->getName() . ' ' . $entry->getSurname(); ?>
                </p>

                <p class="text-gray-500"><?php echo $entry->getEmail(); ?></p>

                <span class="px-4 py-1 rounded-full text-sm font-bold
                        <?php echo $entry->isAdmin() ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'; ?>">
                        <?php echo $entry->isAdmin() ? 'ADMIN' : 'CLIENTE'; ?>
                    </span>

            </a>

        <?php endforeach; ?>

        <?php if(empty($users)): ?>
            <p class="text-2xl text-gray-500 my-20">No hay usuarios.</p>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="flex items-center gap-4 mt-6 bg-white/70 backdrop-blur-md border border-gray-300 rounded-2xl px-6 py-3 shadow-lg">

            <?php if($page != 0): ?>
                <a href="usuarios.php?page=<?php echo $page - 1; ?>"
                   class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 shadow hover:bg-gray-200 transition">
                    &lt;
                </a>
            <?php else: ?>
                <span class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 opacity-40 cursor-default">&lt;</span>
            <?php endif; ?>

            <p class="text-gray-700 font-medium">Página <?php echo $page; ?></p>

            <?php if(!empty($users)): ?>
                <a href="usuarios.php?page=<?php echo $page + 1; ?>"
                   class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 shadow hover:bg-gray-200 transition">
                    &gt;
                </a>
            <?php else: ?>
                <span class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 opacity-40 cursor-default">&gt;</span>
            <?php endif; ?>

        </div>

    </section>


    <!-- CREATE USER FORM -->
    <form class="bg-white/80 backdrop-blur-md shadow-xl border border-gray-200 p-8 rounded-3xl w-full lg:w-[45%] flex flex-col gap-6"
          method="post">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Crear nuevo usuario</h1>

        <div class="flex items-center justify-between w-full gap-4">
            <input type="text" name="name"
                   class="w-1/2 px-5 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-4 focus:ring-blue-300/40 transition"
                   placeholder="Nombre">

            <input type="text" name="surname"
                   class="w-1/2 px-5 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-4 focus:ring-blue-300/40 transition"
                   placeholder="Apellidos">
        </div>

        <input type="text" name="email"
               class="w-full px-5 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-4 focus:ring-blue-300/40 transition"
               placeholder="Email">

        <?php if(isset($passDontMatch)): ?>
            <p class="text-lg text-red-700 font-semibold">
                Las contraseñas no son iguales.
            </p>
        <?php endif; ?>

        <div class="flex items-center justify-between w-full gap-4">

            <input type="password" name="pass"
                   class="w-1/2 px-5 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-4 focus:ring-blue-300/40 transition"
                   placeholder="Contraseña">

            <input type="password" name="confirmPass"
                   class="w-1/2 px-5 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-4 focus:ring-blue-300/40 transition"
                   placeholder="Confirmar contraseña">
        </div>

        <label class="flex items-center gap-3 px-1 mt-2">
            <input type="checkbox" id="isadmin" name="admin" class="w-6 h-6 rounded">
            <span class="text-gray-700 text-lg">¿Usuario administrador?</span>
        </label>

        <button type="submit"
                class="w-full py-3 rounded-2xl bg-blue-600 text-white text-lg font-semibold shadow-md hover:bg-blue-700 hover:shadow-xl transition-all">
            Crear
        </button>

    </form>

</main>

</body>
</html>

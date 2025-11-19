<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\UserService;

session_start();

AuthUtils::restrictPageAdminOnly();

$page = $_GET['page'] ?? 0;

if ($page < 0) {
    header("Location: compras.php?page=0");
}

$sales = UserService::getUserListPaginated($page);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Todas compras</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>

        <?php include 'navbar.php'; ?>

        <main class="flex flex-col items-center justify-center px-[4%] md:px-[10%] lg:px-[25%] py-8">

            <?php foreach($sales as $entry): ?>

                <section class="w-full p-6 border-b-2 border-gray-300/90 flex items-center justify-between">

                    <p>ID: <?php echo $entry->getId(); ?></p>
                    <p><?php echo $entry->getName() . ' ' . $entry->getSurname(); ?></p>
                    
                    <p><?php echo $entry->getEmail(); ?></p>
                    <b><?php echo $entry->isAdmin() ? 'ADMIN' : 'CLIENTE'; ?></b>

                </section>

            <?php endforeach; ?>

            <?php if(empty($sales)): ?>

                <p class="text-2xl my-20">No hay usuarios.</p>

            <?php endif; ?>

            <span class="w-[80%] md:w-[45%] flex items-center justify-evenly py-2 px-6 rounded-2xl bg-gray-200/70 border-1 border-gray-400/80 my-6 shadow-lg">

                <?php if($page != 0): ?>
                    <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg transform transition-transform duration-300 hover:scale-110 cursor-pointer" href="usuarios.php?page=<?php echo $page - 1; ?>"><</a>
                <?php else: ?>
                    <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg invisible" href="usuarios.php?page=<?php echo $page - 1; ?>"><</a>
                <?php endif; ?>
                    
                <p>Página <?php echo $page; ?></p>

                <?php if(!empty($sales)): ?>
                    <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg transform transition-transform duration-300 hover:scale-110 cursor-pointer" href="usuarios.php?page=<?php echo $page + 1; ?>">></a>
                <?php else: ?>
                    <a class="px-4 py-2 rounded-xl bg-gray-100 border-1 border-gray-300/80 shadow-lg invisible" href="usuarios.php?page=<?php echo $page + 1; ?>">></a>
                <?php endif; ?>

            </span>

        </main>

    </body>
</html>
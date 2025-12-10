<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;
use Cdcrane\Dwes\Services\SaleService;

session_start();

AuthUtils::restrictPageAdminOnly();

$page = $_GET['page'] ?? 0;

if ($page < 0) {
    header("Location: compras.php?page=0");
}

$sales = SaleService::getAllSalesPaginated($page);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Todas compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200">

<?php include 'navbar.php'; ?>

<main class="flex flex-col items-center justify-start px-[4%] md:px-[10%] lg:px-[25%] py-10 gap-6">

    <h1 class="text-3xl font-bold text-gray-800 mb-2">Todas las compras</h1>

    <?php foreach($sales as $entry): ?>


        <section
                class="w-full bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-md border border-gray-200 flex flex-col md:grid md:grid-cols-4 gap-2 hover:shadow-xl transition-all"
        >
            <p class="text-gray-700 font-medium">#<?php echo $entry->getId(); ?></p>

            <p class="text-gray-600"><?php echo $entry->getDate(); ?></p>

            <p class="text-gray-600">Entrega:
                <span class="font-semibold"><?php echo $entry->getProvEntrega(); ?></span>
            </p>

            <b class="text-lg text-blue-700 place-self-end">€<?php echo $entry->getTotal(); ?></b>
        </section>

    <?php endforeach; ?>

    <?php if(empty($sales)): ?>
        <p class="text-2xl text-gray-500 my-20">No hay compras.</p>
    <?php endif; ?>

    <!-- Pagination -->
    <div class="flex items-center gap-4 mt-6 bg-white/70 backdrop-blur-md border border-gray-300 rounded-2xl px-6 py-3 shadow-lg">

        <!-- Prev -->
        <?php if ($page != 0): ?>
            <a href="compras.php?page=<?php echo $page - 1; ?>"
               class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 shadow hover:bg-gray-200 transition">
                &lt;
            </a>
        <?php else: ?>
            <span class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 opacity-40 cursor-default">&lt;</span>
        <?php endif; ?>

        <p class="text-gray-700 font-medium">Página <?php echo $page; ?></p>

        <!-- Next -->
        <?php if (!empty($sales)): ?>
            <a href="compras.php?page=<?php echo $page + 1; ?>"
               class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 shadow hover:bg-gray-200 transition">
                &gt;
            </a>
        <?php else: ?>
            <span class="px-4 py-2 rounded-xl bg-gray-100 border border-gray-300 opacity-40 cursor-default">&gt;</span>
        <?php endif; ?>

    </div>

</main>

</body>
</html>

<?php
require __DIR__ . '/../vendor/autoload.php';

use Cdcrane\Dwes\Utils\AuthUtils;

$loggedIn = AuthUtils::isLoggedInAllowAll();
?>

<div class="w-full h-16 bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-600 shadow-xl border-b-1 border-gray-300/70">
    <nav class="w-full h-full flex items-center justify-between px-8 bg-white/10 backdrop-blur-lg border-b border-white/20 text-white">

        <a href="index.php" class="text-2xl font-bold tracking-wide hover:text-gray-300 transition cursor-pointer">
            Zapatoland
        </a>

        <span class="hidden md:flex items-center gap-6 h-full [&>a]:flex [&>a]:items-center [&>a]:text-lg [&>a]:font-semibold [&>a]:tracking-wide [&>a]:transition [&>a]:hover:text-gray-300">
            <a href="productos.php">Productos</a>

            <?php if ($loggedIn && !$_SESSION['es_admin']): ?>
                <a href="micuenta.php">Mi cuenta</a>
                <a href="miscompras.php">Mis compras</a>
                <a href="micarrito.php" class="flex items-center gap-1">
                    Carrito
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                        <path d="M6 5h15l-2 7H7M20 16H8L6 3H3m6 17a1 1 0 110-2 1 1 0 010 2zm11 0a1 1 0 110-2 1 1 0 010 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Log out</a>
            <?php endif; ?>

            <?php if ($loggedIn && $_SESSION['es_admin']): ?>
                <a href="admin.php">Administración</a>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Log out</a>
            <?php endif; ?>

            <?php if (!$loggedIn): ?>
                <a href="login.php" class="text-white hover:text-gray-200">Log in</a>
            <?php endif; ?>
        </span>

        <svg id="hamburger" class="w-7 h-7 md:hidden cursor-pointer hover:scale-110 transition" viewBox="0 0 32 32" fill="none" stroke="currentColor">
            <path d="M4 8h24M4 16h24M4 24h24" stroke-width="3" stroke-linecap="round"></path>
        </svg>
    </nav>
</div>

<div id="overlay" class="fixed inset-0 bg-black/40 opacity-0 invisible transition-opacity duration-300 z-40"></div>

<nav id="sidenav" class="fixed top-0 right-0 w-64 h-full bg-gray-900 text-white transform translate-x-full transition-transform duration-300 z-50 flex flex-col gap-6 pt-20 px-6 text-2xl font-semibold">

    <a href="productos.php" class="hover:text-blue-300 transition">Productos</a>

    <?php if ($loggedIn && !$_SESSION['es_admin']): ?>
        <a href="micuenta.php" class="hover:text-blue-300 transition">Mi cuenta</a>
        <a href="miscompras.php" class="hover:text-blue-300 transition">Mis compras</a>
        <a href="micarrito.php" class="hover:text-blue-300 transition flex items-center gap-2">
            Carrito
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                <path d="M6 5h15l-2 7H7M20 16H8L6 3H3m6 17a1 1 0 110-2 1 1 0 010 2zm11 0a1 1 0 110-2 1 1 0 010 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
        <a href="logout.php" class="text-red-500 hover:text-red-700 mt-auto mb-10 self-center text-3xl">Log out</a>
    <?php endif; ?>

    <?php if ($loggedIn && $_SESSION['es_admin']): ?>
        <a href="admin.php" class="hover:text-blue-300 transition">Administración</a>
        <a href="logout.php" class="text-red-500 hover:text-red-700 mt-auto mb-10 self-center text-3xl">Log out</a>
    <?php endif; ?>

    <?php if (!$loggedIn): ?>
        <a href="login.php" class="text-blue-300 hover:text-blue-400 mt-auto mb-10 self-center text-3xl">Log in</a>
    <?php endif; ?>
</nav>

<script>
    const hamburger = document.getElementById('hamburger');
    const sidenav = document.getElementById('sidenav');
    const overlay = document.getElementById('overlay');

    hamburger.addEventListener('click', () => {
        sidenav.classList.toggle('translate-x-full');
        overlay.classList.toggle('opacity-0');
        overlay.classList.toggle('invisible');
    });

    overlay.addEventListener('click', () => {
        sidenav.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');
        overlay.classList.add('invisible');
    });
</script>
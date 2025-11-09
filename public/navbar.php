<?php

    require __DIR__ . '/../vendor/autoload.php';

    use Cdcrane\Dwes\Utils\AuthUtils;

    $loggedIn = AuthUtils::isLoggedInAllowAll();

?>

<nav class="w-full h-16 flex items-center justify-between px-8 bg-slate-800 text-white">

    <a class="text-2xl font-bold cursor-pointer hover:text-gray-400" href="index.php">Zapatoland</a>

    <span class="hidden md:flex items-center-safe justify evenly gap-4 h-full [&>a]:hover:border-b-2 [&>a]:hover:border-gray-400 [&>a]:hover:text-gray-400 [&>a]:cursor-pointer [&>a]:h-full [&>a]:content-center">

        <a class="font-bold tracking-wide" href="productos.php">Productos</a>

        <?php if ($loggedIn): ?>

            <a class="font-bold tracking-wide" href="micuenta.php">Mi cuenta</a>
            <a class="font-bold tracking-wide">Mis compras</a>
            <a class="font-bold tracking-wide">Carrito</a>
            <a class="font-bold text-red-800 tracking-wide" href="logout.php">Log out</a>

        <?php endif; ?>

        <?php if(!$loggedIn): ?>

            <a class="text-blue-300 font-bold tracking-wide" href="login.php">Log in</a>

        <?php endif; ?>

    </span>

    <svg id="hamburger" viewBox="0 -2 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" stroke="#ffffff" class="w-6 h-6 md:hidden"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>hamburger-2</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" > <g id="Icon-Set"  transform="translate(-308.000000, -1037.000000)" fill="#ffffff"> <path d="M336,1063 L312,1063 C310.896,1063 310,1062.1 310,1061 C310,1059.9 310.896,1059 312,1059 L336,1059 C337.104,1059 338,1059.9 338,1061 C338,1062.1 337.104,1063 336,1063 L336,1063 Z M336,1057 L312,1057 C309.791,1057 308,1058.79 308,1061 C308,1063.21 309.791,1065 312,1065 L336,1065 C338.209,1065 340,1063.21 340,1061 C340,1058.79 338.209,1057 336,1057 L336,1057 Z M336,1053 L312,1053 C310.896,1053 310,1052.1 310,1051 C310,1049.9 310.896,1049 312,1049 L336,1049 C337.104,1049 338,1049.9 338,1051 C338,1052.1 337.104,1053 336,1053 L336,1053 Z M336,1047 L312,1047 C309.791,1047 308,1048.79 308,1051 C308,1053.21 309.791,1055 312,1055 L336,1055 C338.209,1055 340,1053.21 340,1051 C340,1048.79 338.209,1047 336,1047 L336,1047 Z M312,1039 L336,1039 C337.104,1039 338,1039.9 338,1041 C338,1042.1 337.104,1043 336,1043 L312,1043 C310.896,1043 310,1042.1 310,1041 C310,1039.9 310.896,1039 312,1039 L312,1039 Z M312,1045 L336,1045 C338.209,1045 340,1043.21 340,1041 C340,1038.79 338.209,1037 336,1037 L312,1037 C309.791,1037 308,1038.79 308,1041 C308,1043.21 309.791,1045 312,1045 L312,1045 Z" id="hamburger-2"> </path> </g> </g> </g></svg>

</nav>

<div id="overlay" class="fixed inset-0 bg-black/50 bg-opacity-40 opacity-0 invisible transition-opacity duration-300 z-40"></div>

<!-- Hidden nav menu for phones -->
<nav id="sidenav" class="fixed top-0 right-0 w-50 h-full bg-slate-800 text-white transform translate-x-full transition-transform duration-300 z-50 flex flex-col items-end gap-4 pt-18 px-4 [&>a]:hover:text-gray-400 [&>a]:text-2xl">

    <a href="productos.php">Productos</a>

    <?php if ($loggedIn): ?>

        <a href="micuenta.php">Mi cuenta</a>
        <a>Mis compras</a>
        <a>Carrito</a>
        <a class="text-red-800 mt-auto self-center mb-8 text-3xl" href="logout.php">Log out</a>

    <?php endif; ?>

    <?php if(!$loggedIn): ?>

        <a class="text-blue-300 font-bold mt-auto mb-8 text-3xl self-center" href="login.php">Log in</a>

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
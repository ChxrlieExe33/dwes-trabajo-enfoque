<?php

require __DIR__ . '\/..\/vendor\/autoload.php';

use Cdcrane\Dwes\Services\AuthService;
use Cdcrane\Dwes\Utils\AuthUtils;

session_start();

AuthUtils::redirectToHomeIfAuthenticated();

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $authenticated = AuthService::authenticate($_POST["username"], $_POST["password"]);
    if ($authenticated) {
        header("Location: index.php");
    }
    $error = "Invalid creds";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-200 min-h-screen flex items-center justify-center p-4">

    <main class="w-full max-w-lg bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 flex flex-col items-center gap-6 border border-white/40">

        <h1 class="text-4xl font-extrabold tracking-tight text-blue-700">Zapatoland</h1>
        <p class="text-lg text-gray-600">Inicia sesión en tu cuenta</p>

        <?php if(isset($_GET["registered"])): ?>
            <p class="text-green-600 text-center w-full font-semibold">Tu cuenta se ha creado con éxito</p>
        <?php endif; ?>

        <?php if($error != null): ?>
            <p class="text-red-600 text-lg font-semibold text-center w-full"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post" class="w-full flex flex-col gap-5 mt-2">
            <div class="flex flex-col gap-2">
                <input type="text" name="username" placeholder="Email" class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" />
                <input type="password" name="password" placeholder="Contraseña" class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" />
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 transition-all duration-200 text-center cursor-pointer">Iniciar sesión</button>
        </form>

        <div class="flex flex-col items-center gap-2 mt-4 w-full">
            <a href="register.php" class="text-blue-600 font-semibold hover:text-blue-800 transition">Crear una cuenta</a>
            <a href="index.php" class="text-gray-600 font-semibold hover:text-gray-800 transition">Navegar sin iniciar sesión</a>
        </div>

    </main>

</body>
</html>

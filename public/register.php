<?php

    require __DIR__ . '/../vendor/autoload.php';

    use Cdcrane\Dwes\Services\AuthService;
    use Cdcrane\Dwes\Requests\RegisterAccountRequest;
    use Cdcrane\Dwes\Utils\AuthUtils;

    session_start();

    AuthUtils::redirectToHomeIfAuthenticated();

    $passwordsDontMatch = false;
    $emailTaken = false;
    function handleRegister(): void
    {

        if ($_POST["password"] !== $_POST["confirm_password"]) {
            global $passwordsDontMatch;
            $passwordsDontMatch = true;
            return;
        }



        if (AuthService::userExistsByEmail($_POST["email"])) {
            global $emailTaken;
            $emailTaken = true;
            return;
        }

        // Crear un objeto para pasar al servicio, y usar htmlspecialchars para evitar ataques XSS.
        $registerRequest = new RegisterAccountRequest(htmlspecialchars($_POST["nombre"]), htmlspecialchars($_POST["apellidos"]), 
                                                    htmlspecialchars($_POST["email"]), $_POST["password"], 
                                                    htmlspecialchars($_POST["direccion"]), htmlspecialchars($_POST["ciudad"]), htmlspecialchars($_POST["provincia"]));

        $success = AuthService::register($registerRequest);

        if ($success) header("Location: login.php?registered=$_POST[email]");

    }



    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        handleRegister();

    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registro Zapatoland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-200 min-h-screen flex items-center justify-center p-4">

<main class="w-full max-w-2xl bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 flex flex-col items-center gap-8 border border-white/40">

    <h1 class="text-4xl font-extrabold tracking-tight text-blue-700">Zapatoland</h1>
    <p class="text-lg text-gray-600">Crea tu cuenta</p>

    <form method="post" class="w-full flex flex-col gap-8">

        <section class="w-full flex flex-col gap-4">
            <h2 class="text-xl font-semibold text-blue-700">Datos personales</h2>

            <?php if($emailTaken): ?>
                <p class="text-red-600 font-semibold text-center w-full">Este correo ya está en uso.</p>
            <?php endif; ?>

            <input type="text" name="nombre" placeholder="Nombre"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>

            <input type="text" name="apellidos" placeholder="Apellidos"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>

            <input type="email" name="email" placeholder="Email"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>
        </section>

        <section class="w-full flex flex-col gap-4">
            <h2 class="text-xl font-semibold text-blue-700">Dirección</h2>

            <input type="text" name="direccion" placeholder="Dirección"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>

            <input type="text" name="ciudad" placeholder="Ciudad"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>

            <input type="text" name="provincia" placeholder="Provincia"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>
        </section>

        <section class="w-full flex flex-col gap-4">
            <h2 class="text-xl font-semibold text-blue-700">Contraseña</h2>

            <?php if($passwordsDontMatch): ?>
                <p class="text-red-600 font-semibold text-center w-full">Las contraseñas no coinciden.</p>
            <?php endif; ?>

            <input type="password" name="password" placeholder="Contraseña"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>

            <input type="password" name="confirm_password" placeholder="Confirmar contraseña"
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-300
                           focus:ring-2 focus:ring-blue-400 outline-none text-gray-800" required>
        </section>

        <button type="submit"
                class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg
                       hover:bg-blue-700 transition-all duration-200">
            Crear cuenta
        </button>

        <a href="login.php"
           class="text-blue-600 font-semibold hover:text-blue-800 text-center transition">
            ¿Ya tienes cuenta? Inicia sesión
        </a>

    </form>
</main>

</body>
</html>
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
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

<main class="w-full min-h-screen flex flex-col items-center justify-center bg-blue-200/50">

    <form class="my-8 bg-white border-1 border-gray-400/80 shadow-gray-400/50 shadow-xl w-[90%] lg:w-[40%] flex flex-col items-center justify-center py-8 px-4 lg:px-8 gap-6 rounded-3xl" method="post">

        <h1 class="text-2xl text-blue-800 font-bold">Zapatoland</h1>
        <p class="text-xl">Crea tu cuenta</p>

        <section class="w-full flex flex-col items-center justify-center gap-2 rounded-3xl">

            <h2 class="font-bold">Datos personales</h2>

            <?php if($emailTaken): ?>
                <p class="text-red-800 font-bold">Este correo yá está en uso.</p>
            <?php endif; ?>

            <input type="text" name="nombre" placeholder="Nombre..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>
            <input type="text" name="apellidos" placeholder="Apellidos..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>
            <input type="email" name="email" placeholder="Email..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>

        </section>

        <section class="w-full flex flex-col items-center justify-center gap-2 rounded-3xl">

            <h2 class="font-bold">Dirección</h2>

            <input type="text" name="direccion" placeholder="Dirección..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>
            <input type="text" name="ciudad" placeholder="Ciudad..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>
            <input type="text" name="provincia" placeholder="Provincia..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>

        </section>

        <span class="w-[80%] h-2 bg-slate-300 rounded-3xl"></span>

        <section class="w-full flex flex-col items-center justify-center gap-2 rounded-3xl">

            <h2 class="font-bold">Contraseña</h2>
            <?php if($passwordsDontMatch): ?>
                <p class="text-red-800 font-bold">Las contraseñas no son iguales</p>
            <?php endif; ?>
            <input type="password" name="password" placeholder="Contraseña..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>
            <input type="password" name="confirm_password" placeholder="Repite contraseña..." class="border-1 border-gray-300/70 shadow-gray-300/50 shadow-md px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[90%] lg:w-[80%]" required>

        </section>

        <button type="submit" class="px-8 py-2 bg-blue-800 rounded-3xl text-white font-bold cursor-pointer shadow-xl transform transition-transform duration-300 hover:scale-110">Submit</button>

        <a href="login.php" class="text-black text-lg lg:text-xl font-bold hover:text-gray-400">Ya tienes cuenta? Inicia sesión</a>

    </form>

</main>

</body>
</html>
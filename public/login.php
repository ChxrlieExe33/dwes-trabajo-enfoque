<?php

    require __DIR__ . '/../vendor/autoload.php';

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
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        
        <main class="w-full min-h-screen flex flex-col items-center justify-center bg-blue-200/50">

            <form class="bg-white border-1 border-gray-400/80 shadow-gray-400/50 shadow-xl w-[90%] lg:w-[40%] flex flex-col items-center justify-center py-8 md:px-4 lg:px-8 gap-6 rounded-3xl" method="post">

                <h1 class="text-2xl text-blue-800 font-bold">Zapatoland</h1>
                <p class="text-xl">Inicia sesión</p>

                <?php if(isset($_GET["registered"])): ?>
                    <p>Tu cuenta se ha creado con éxito <?php echo $_GET["registered"]; ?></p>
                <?php endif; ?>

                <?php if($error != null): ?>

                    <p class="text-2xl text-red-800"><?php echo $error; ?></p>

                <?php endif; ?>

                <span class="w-full flex flex-col items-center justify-center gap-2">
                    <input type="text" name="username" placeholder="Email..." class="px-6 py-2 rounded-xl border-1 border-gray-300/70 shadow-gray-300/50 shadow-md outline-none bg-slate-200 w-[90%] lg:w-[80%]">
                    <input type="password" name="password" placeholder="Contraseña..." class="px-6 py-2 rounded-xl border-gray-300/70 shadow-gray-300/50 shadow-md outline-none bg-slate-200 w-[90%] lg:w-[80%]">
                </span>

                <button type="submit" class="px-8 py-2 bg-blue-800 rounded-3xl text-white font-bold shadow-xl transform transition-transform duration-300 hover:scale-110 cursor-pointer">Submit</button>

                <span class="w-full flex flex-col items-center justify-center">

                    <a href="register.php" class="text-lg lg:text-xl text-blue-400 font-bold hover:text-gray-400">Registrate</a>
                    <a href="index.php" class="text-black text-lg lg:text-xl font-bold hover:text-gray-400">Navegar sin iniciar sesión.</a>
                </span>

            </form>

        </main>

    </body>
</html>
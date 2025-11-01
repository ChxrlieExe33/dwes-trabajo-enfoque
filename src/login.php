<?php

    session_start();

    require_once "services/AuthService.php";

    $error = null;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {

        $authenticated = AuthService::authenticate($_POST["username"], $_POST["password"]);

        if ($authenticated) {

            header("Location: /dwes-trabajo-enfoque/src/index.php");

        }
        
        $error = "Invalid creds";

    }

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        
        <main class="w-full min-h-screen flex flex-col items-center justify-center bg-blue-300/50">

            <form class="bg-white w-[40%] flex flex-col items-center justify-center p-8 gap-6 rounded-3xl" method="post">

                <h1 class="text-2xl text-blue-800 font-bold">Zapatoland</h1>
                <p class="text-xl">Inicia sesión</p>

                <?php if(isset($_GET["registered"])): ?>
                    <p>Tu cuenta se ha creado con éxito <?php echo $_GET["registered"]; ?></p>
                <?php endif; ?>

                <?php if($error != null): ?>

                    <p class="text-2xl text-red-800"><?php echo $error; ?></p>

                <?php endif; ?>

                <input type="text" name="username" placeholder="Email..." class="px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[80%]">
                <input type="password" name="password" placeholder="Contraseña..." class="px-6 py-2 rounded-2xl outline-none bg-slate-200 w-[80%]">

                <button type="submit" class="px-8 py-2 bg-blue-800 rounded-3xl text-white font-bold cursor-pointer">Submit</button>

                <a href="index.php" class="text-black text-xl font-bold underline hover:text-gray-400">Navegar sin iniciar sesión.</a>

            </form>

        </main>

    </body>
</html>
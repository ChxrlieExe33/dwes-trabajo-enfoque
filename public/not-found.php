<?php

    if(!isset($_GET['message'])){
        header("location: index.php");
    }

    $message = $_GET['message'];

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="flex items-center flex-col justify-center min-h-screen gap-6">

    <h1 class="text-3xl text-red-800"><?php echo htmlspecialchars($message); ?></h1>

    <a class="px-6 py-2 bg-blue-500 rounded 2xl font-bold text-white" href="index.php">Vuelve al inicio</a>

</body>

</html>

<?php

    require __DIR__ . '/../vendor/autoload.php';

    use Cdcrane\Dwes\Services\ProductService;
    use Cdcrane\Dwes\Services\CarritoService;

    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Devuelve null en caso de que funcione correctamente.
        $error = CarritoService::addToCart($_POST["id"], $_POST["size"], 1);

    }

    if(!isset($_GET["id"])){
        header("location: index.php");
    }

    $id = $_GET['id'];

    $productData = ProductService::getProductDetail($id);

    if($productData == null){
        $message = "No se encontro el producto con ID $id";
        header("location: not-found.php?message=$message");
    }

    $productImages = ProductService::getProductImages($id);
    $stock = ProductService::getStockCountOfProduct($id);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $productData->getNombre(); ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const carousel = document.getElementById('carousel');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            const totalImages = carousel.children.length;
            if (totalImages === 0) {
                console.warn('No images in the carousel.');
                return;
            }

            let index = 0;
            let autoSlideTimer = null;

            function setTransformX(el, value) {
                el.style.transform = value;
                el.style.webkitTransform = value;
            }

            function updateCarousel() {
                const translate = `translateX(-${index * 100}%)`;
                setTransformX(carousel, translate);
            }

            prevBtn.addEventListener('click', () => {
                index = (index - 1 + totalImages) % totalImages;
                updateCarousel();
                resetAutoSlide();
            });

            nextBtn.addEventListener('click', () => {
                index = (index + 1) % totalImages;
                updateCarousel();
                resetAutoSlide();
            });

            // Optional: Auto-slide every 4 seconds
            function startAutoSlide() {
                stopAutoSlide();
                autoSlideTimer = setInterval(() => {
                    index = (index + 1) % totalImages;
                    updateCarousel();
                }, 4000);
            }
            function stopAutoSlide() {
                if (autoSlideTimer) {
                    clearInterval(autoSlideTimer);
                    autoSlideTimer = null;
                }
            }
            function resetAutoSlide() {
                startAutoSlide();
            }

            // start
            updateCarousel();
            startAutoSlide();

            // Clean up when the page unloads (good practice)
            window.addEventListener('beforeunload', stopAutoSlide);
        });

    </script>
</head>

<body>

    <?php include_once 'navbar.php';?>

    <div class="w-full px-[5%] md:px-[15%] flex flex-col md:flex-row items-start justify-between pt-6 md:pt-12">

        <div class="relative w-full max-w-xl overflow-hidden rounded-lg shadow-xl">
            <!-- Carousel Images -->
            <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                <?php foreach ($productImages as $img): ?>
                    <img src="images/<?php echo htmlspecialchars($img); ?>"
                         alt="<?php echo htmlspecialchars($img); ?>"
                         class="w-full flex-shrink-0 object-cover">
                <?php endforeach; ?>
            </div>

            <!-- Navigation Buttons -->
            <?php if(count($productImages) > 1): ?>
            <button id="prevBtn"
                    class="absolute top-1/2 left-2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-2 shadow cursor-pointer">
                &#10094;
            </button>
            <button id="nextBtn"
                    class="absolute top-1/2 right-2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-2 shadow cursor-pointer">
                &#10095;
            </button>
            <?php endif; ?>

        </div>

        <main class="w-full flex flex-col items-start justify-start pt-8 gap-2 md:gap-6 min-h-screen md:px-8">

            <h1 class="text-4xl font-bold"><?php echo $productData->getNombre(); ?></h1>

            <p class="text-lg text-gray-600"><?php echo $productData->getFabricante(); ?></p>

            <p class="text-2xl font-bold text-blue-400"><?php echo $productData->getPrecio(); ?>€</p>

            <section class="w-full flex flex-col items-start justify-start gap-2">

                <p class="text-xl font-bold">Descripción</p>
                <p class="text-xl border-1 border-gray-300/90 px-2 py-4 rounded-xl w-full shadow-lg"><?php echo $productData->getDescripcion(); ?></p>

            </section>

            <p class="text-lg"><b>Color:</b> <?php echo $productData->getColor(); ?></p>

            <?php if(isset($error)): ?>

                <p class="text-xl text-red-800"><?php echo $error; ?></p>

            <?php endif; ?>

            <?php if($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($error)): ?>
                <p class="text-xl text-green-600">Añadido al carrito en talla <?php echo $_POST["size"];?>.</p>
            <?php endif; ?>

            <?php if($_SESSION['es_admin'] != true): ?>
                <form class="flex items-center justify-center gap-4" method="post">

                    <input type="text" name="id" value="<?php echo $productData->getId(); ?>" class="hidden">

                    <?php if(!empty($stock)): ?>
                    
                        <p class="text-lg font-bold">Talla: </p>

                        <select name="size" class="px-6 py-2 border-1 border-gray-300/80 rounded-2xl shadow-lg">
                            <?php foreach($stock as $size): ?>
                                <option value="<?php echo $size->getSize(); ?>"><?php echo $size->getSize(); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <?php if(isset($_SESSION['cartId'])): ?>
                            <button type="submit" class="shadow-lg px-6 py-2 bg-blue-500 text-white font-bold rounded-2xl transform transition-transform duration-300 hover:scale-110 cursor-pointer">Añadir al carrito</button>
                        <?php else: ?>
                            <a href="login.php" class="shadow-lg px-6 py-2 bg-blue-500 text-white font-bold rounded-2xl transform transition-transform duration-300 hover:scale-110 cursor-pointer">Añadir al carrito</a>
                        <?php endif; ?>
                        
                    <?php else: ?>

                        <p class="text-2xl text-red-800">Esto ya no está disponible</p>

                    <?php endif; ?>    
                    
                </form>

            <?php else: ?>

                <a href="actualizaproducto.php?id=<?php echo $_GET['id']; ?>" class="px-6 py-2 bg-blue-500 rounded-2xl text-white font-bold transform transition-transform duration-300 hover:scale-110 cursor-pointer">Actualizar</a>

            <?php endif; ?>

        </main>

    </div>

</body>

</html>

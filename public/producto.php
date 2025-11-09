<?php

    require __DIR__ . '/../vendor/autoload.php';

    use Cdcrane\Dwes\Services\ProductService;

    session_start();

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
                    <img src="/dwes-trabajo-enfoque/src/images/<?php echo htmlspecialchars($img); ?>"
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
                <p class="text-xl border-1 border-blue-900 px-2 py-4 rounded-xl w-full shadow-lg"><?php echo $productData->getDescripcion(); ?></p>

            </section>

            <p class="text-lg"><b>Color:</b> <?php echo $productData->getColor(); ?></p>

            <button class="px-6 py-2 bg-blue-500 text-white font-bold rounded-2xl">Añadir al carrito</button>


        </main>

    </div>

</body>

</html>

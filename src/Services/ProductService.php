<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\Models\HomepageProduct;
use Cdcrane\Dwes\Models\ProductDetail;
use Cdcrane\Dwes\Models\ProductSizeAvailability;
use Cdcrane\Dwes\Requests\SaveNewProductRequest;
use Cdcrane\Dwes\Requests\UpdateProductDataRequest;
use PDO;
use PDOException;

class ProductService {

    // Listado de extensiones de fichero permitido.
    public static $allowedFileExtensions = ['png', 'svg', 'jpg', 'jpeg', 'webp'];

    /**
     * Obtener objetos de algunos productos para mostrar en la página principal.
     * @return array Objetos con datos de productos.
     */
    public static function getNewestProductsHomePageView() : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto GROUP BY p.nombre, p.precio, p.id_producto LIMIT 2';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        // Mapear array asociativo del resultado de la consulta a un array de objetos.
        return array_map(function (array $row) {

            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']
            );
        }, $data);

    }

    /**
     * Obtener todos los productos.
     * @return array Objetos de los productos para mostrar en la página de productos.
     */
    public static function getAllProducts() : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto GROUP BY p.nombre, p.precio, p.id_producto';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        // Mapear array asociativo del resultado de la consulta a un array de objetos.
        return array_map(function (array $row) {
            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']);
        }, $data);

    }

    /**
     * Buscar productos por nombre que contiene la cadena proporcionada.
     * @param string $name La cadena para buscar.
     * @return array Los objetos de productos encontrados.
     */
    public static function searchProductsByName(string $name) : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto WHERE p.nombre LIKE :searchTerm GROUP BY p.nombre, p.precio, p.id_producto';

        // Usando un %$name% decimos que el texto proporcionado puede estar dentro de una palabra completa.
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':searchTerm' => "%$name%"
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        // Mapear array asociativo del resultado de la consulta a un array de objetos.
        return array_map(function (array $row) {
            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']);
        }, $data);

    }

    /**
     * Obtener los detalles de un producto para su página de detalle.
     * @param int $id El ID del producto.
     * @return ProductDetail|null Los datos del producto.
     */
    public static function getProductDetail(int $id) : ?ProductDetail {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM productos WHERE id_producto = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return null;
        }

        // Mapear array asociativo del resultado de la consulta a un array de objetos.
        return new ProductDetail($data['id_producto'], $data['nombre'], $data['descripcion'], $data['precio'], $data['color'], $data['nombre_fabricante']);

    }

    /**
     * Obtener las imágenes del producto con ID indicado.
     * @param int $id El ID del producto.
     * @return array Los nombres de ficheros de las imágenes.
     */
    public static function getProductImages(int $id) : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM multimedia_productos WHERE id_producto = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        // Proporcionar solo la columna fichero.
        return array_map(function (array $row) {
           return $row['fichero'];
        }, $data);

    }

    /**
     * Obtener el listado de instancias del producto indicado en stock y en que tamaños son.
     * @param int $id El ID del producto.
     * @return array Array con la disponibilidad de este producto.
     */
    public static function getStockCountOfProduct(int $id) : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT talla, cantidad FROM disponibilidad_productos WHERE id_producto = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($data)) {
            return [];
        }

        return array_map(function (array $row) {
            return new ProductSizeAvailability($row['talla'], $row['cantidad']);
        }, $data);

    }

    /**
     * Añadir o actualizar instancias de disponibilidad de un producto en cierto tamaño.
     * @param int $prodId El ID del producto.
     * @param int $count La cantidad para añadir.
     * @param int $size El tamaño.
     * @return void
     */
    public static function addStockOfProduct(int $prodId, int $count, int $size) : void {

        $pdo = DBConnFactory::getConnection();

        $findExistingEntrySql = 'SELECT * FROM disponibilidad_productos WHERE id_producto = :id AND talla = :talla';
        $findExistingEntryStmt = $pdo->prepare($findExistingEntrySql);

        $findExistingEntryStmt->execute([
            ":id" => $prodId,
            ":talla" => $size
        ]);

        $data = $findExistingEntryStmt->fetch(PDO::FETCH_ASSOC);

        // Si hay una entrada de ese producto en ese tamaño, aumenta la cantidad, si no, lo creamos.
        if (empty($data)) {

            $saveNewStockSql = 'INSERT INTO disponibilidad_productos (id_producto, talla, cantidad) VALUES (:id, :talla, :cantidad)';
            $saveNewStockStmt = $pdo->prepare($saveNewStockSql);

            $saveNewStockStmt->execute([
                ":id" => $prodId,
                ":talla" => $size,
                ":cantidad" => $count
            ]);

        } else {

            $updateExistingStockSql = 'UPDATE disponibilidad_productos SET cantidad = cantidad + :cantidad WHERE id_producto = :id AND talla = :talla';
            $updateExistingStockStmt = $pdo->prepare($updateExistingStockSql);

            $updateExistingStockStmt->execute([
                ":id" => $prodId,
                ":talla" => $size,
                ":cantidad" => $count
            ]);

        }

    }

    /**
     * Insertar un nuevo producto con sus imágenes.
     * @param SaveNewProductRequest $request Los datos del producto nuevo como objeto.
     * @return false|string|void
     */
    public static function insertNewProduct(SaveNewProductRequest $request) {

        $pdo = DBConnFactory::getConnection();
        $pdo->beginTransaction();

        try {

            // ----------------------------------------------
            // Guardar los datos del producto y obtener el ID
            // ----------------------------------------------

            $saveProductSql = 'INSERT INTO productos (nombre, descripcion, precio, color, nombre_fabricante) VALUES (:nombre, :descrip, :pr, :col, :fabr)';

            $saveProdStmt = $pdo->prepare($saveProductSql);
            $saveProdStmt->execute([
                ':nombre' => $request->getName(),
                ':descrip' => $request->getDescription(),
                ':pr' => $request->getPrice(),
                ':col' => $request->getColour(),
                ':fabr' => $request->getFactoryName()
            ]);

            $prodId = $pdo->lastInsertId();

            // ----------------------------------------
            // Guardar las imágenes en el caso que hay.
            // ----------------------------------------

            $imgs = $request->getImages();

            # Verificar que hay ficheros mirando si la primera entrada tiene un nombre que no es vacío.
            # Ya que la primera entrada siempre aparece aunque no hay ficheros.
            if ($imgs['file']['name'][0] != "") { 

                $fileCount = count($imgs['file']['name']);

                for ($i = 0; $i < $fileCount; $i++) {

                    // Crear un nombre único para cada fichero, por si dos usuarios suben ficheros con el mismo nombre.
                    $name = "Imagen-" . $i .  "-producto-" . $prodId . "-" . $imgs['file']['name'][$i];

                    $extension = pathinfo($imgs['file']['name'][$i], PATHINFO_EXTENSION);
                    $extension = strtolower($extension);

                    $location = __DIR__ . "/../../public/images/" . $name;

                    // Hacer un Rollback y falla en caso de que el usuario proporciona un tipo de fichero no permitido.
                    if(!in_array($extension, ProductService::$allowedFileExtensions)) {
                        $pdo->rollBack();
                        die("Invalid file extension '" . $extension . "' on file " . $imgs['file']['name'][$i]);
                    }

                    // Mover el fichero y verificarlo.
                    if(!move_uploaded_file($imgs['file']['tmp_name'][$i], $location)) {
                        die("Could not move " . $imgs['file']['tmp_name'][$i] . " to " . $location);
                    }

                    // Guardar este multimedia de producto en la base de datos.
                    $saveMediaSql = 'INSERT INTO multimedia_productos (id_producto, fichero) VALUES (:idProd, :nombreFichero)';
                    $saveMediaStmt = $pdo->prepare($saveMediaSql);

                    $saveMediaStmt->execute([
                        ':idProd' => $prodId,
                        ':nombreFichero' => $name
                    ]);

                }

            }

            $pdo->commit();

            return $prodId;

        } catch (PDOException $e) {

            $pdo->rollBack();
            die($e);

        }


    }

    /**
     * Actualizar un producto existente en la base de datos.
     * @param UpdateProductDataRequest $request El objeto con los datos del producto.
     * @param int $prodId El ID del producto.
     * @return void
     */
    public static function updateExistingProduct(UpdateProductDataRequest $request, int $prodId) {

        $pdo = DBConnFactory::getConnection();
        
        $sql = 'UPDATE productos SET nombre = :nombre, descripcion = :descrip, precio = :precio, color = :color, nombre_fabricante = :fab WHERE id_producto = :id';
        
        try {

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $request->getName(),
                ':descrip' => $request->getDescription(),
                ':precio' => $request->getPrice(),
                ':color' => $request->getColour(),
                ':fab' => $request->getFactoryName(),
                ':id' => $prodId
            ]);

        } catch (PDOException $e) {

            die($e);
        }

    }

    /**
     * Eliminar un producto.
     * @param int $id El ID del producto.
     * @return void
     */
    public static function deleteProduct(int $id) {

        $pdo = DBConnFactory::getConnection();

        $getMediaSql = 'SELECT fichero FROM multimedia_productos WHERE id_producto = :id';
        $getMediaStmt = $pdo->prepare($getMediaSql);
        $getMediaStmt->execute([
            ':id' => $id
        ]);

        $imgs = $getMediaStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener las rutas a todas las imágenes del producto.
        $imgPaths = array_map(function ($row) {
            return __DIR__ . "/../../public/images/" . $row['fichero'];
        }, $imgs);

        // Eliminar el producto
        $sql = 'DELETE FROM productos WHERE id_producto = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        // Eliminar cada imagen del disco, el CASCADE de la base de datos eliminará sus registros.
        foreach ($imgPaths as $path) {
            unlink($path);
        }

    }

}
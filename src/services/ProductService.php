<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\Models\HomepageProduct;
use Cdcrane\Dwes\Models\ProductDetail;
use Cdcrane\Dwes\models\ProductSizeAvailability;
use Cdcrane\Dwes\requests\SaveNewProductRequest;
use PDO;
use PDOException;

class ProductService {

    public static $allowedFileExtensions = ['png', 'svg', 'jpg', 'jpeg', 'webp'];

    public static function getNewestProductsHomePageView() : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto GROUP BY p.nombre, p.precio, p.id_producto LIMIT 2';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        return array_map(function (array $row) {

            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']
            );
        }, $data);

    }

    public static function getAllProducts() : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto GROUP BY p.nombre, p.precio, p.id_producto';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        return array_map(function (array $row) {
            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']);
        }, $data);

    }

    public static function searchProductsByName(string $name) : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto WHERE p.nombre LIKE :searchTerm GROUP BY p.nombre, p.precio, p.id_producto';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':searchTerm' => "%$name%"
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        return array_map(function (array $row) {
            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']);
        }, $data);

    }

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

        return new ProductDetail($data['id_producto'], $data['nombre'], $data['descripcion'], $data['precio'], $data['color'], $data['nombre_fabricante']);

    }

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

        return array_map(function (array $row) {
           return $row['fichero'];
        }, $data);

    }

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

    public static function insertNewProduct(SaveNewProductRequest $request) {

        $pdo = DBConnFactory::getConnection();
        $pdo->beginTransaction();

        try {

            // ---------------------
            // Save the product data
            // ---------------------

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

            // ---------------------
            // Save the images if they are present
            // ---------------------

            $imgs = $request->getImages();

            # Check if there is files by seeing if the first name is not empty
            # since the first one is always there just empty if there was no files
            if ($imgs['file']['name'][0] != "") { 

                $fileCount = count($imgs['file']['name']);

                for ($i = 0; $i < $fileCount; $i++) {

                    // Unique name for each one in the array in case names are duplicated.
                    $name = $imgs['file']['name'][$i] . "-imagen-" . $i .  "-producto-" . $prodId;

                    $extension = pathinfo($imgs['file']['name'][$i], PATHINFO_EXTENSION);
                    $extension = strtolower($extension);

                    $location = __DIR__ . "/../images/" . $name . $extension;

                    // Rollback and throw error when user submits invalid file type.
                    if(!in_array($extension, ProductService::$allowedFileExtensions)) {
                        $pdo->rollBack();
                        die("Invalid file extension '" . $extension . "' on file " . $imgs['file']['name'][$i]);
                    }

                    if(!move_uploaded_file($imgs['file']['tmp_name'][$i], $location)) {
                        die("Could not move " . $imgs['file']['tmp_name'][$i] . " to " . $location);
                    }

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

}
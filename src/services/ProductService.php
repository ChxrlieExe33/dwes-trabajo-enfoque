<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\Models\HomepageProduct;
use Cdcrane\Dwes\Models\ProductDetail;
use Cdcrane\Dwes\models\ProductSizeAvailability;
use PDO;

class ProductService {

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

}
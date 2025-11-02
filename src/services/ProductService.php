<?php

declare(strict_types=1);

require_once "DBConnFactory.php";
require_once "model/HomepageProduct.php";

class ProductService {

    public static function getNewestProductsHomePageView() : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT p.id_producto, p.nombre, p.precio, MIN(m.fichero) as fichero FROM `productos` p LEFT JOIN `multimedia_productos` m ON p.id_producto = m.id_producto GROUP BY p.nombre, p.precio, p.id_producto LIMIT 2';

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row) {

            return new HomepageProduct(
                $row['id_producto'],
                $row['nombre'],
                $row['precio'],
                $row['fichero']
            );
        }, $data);

    }

}
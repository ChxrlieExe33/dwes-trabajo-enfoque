<?php

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\models\CartEntryView;
use PDO;
use PDOException;

class CarritoService {

    public static function getCartContents($cartId) : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT pc.cantidad AS cant, pc.tamano AS tamano, p.nombre AS nombre, p.precio AS precio FROM productos_carritos pc LEFT JOIN productos p ON p.id_producto = pc.id_producto WHERE pc.id_carrito = :cartId';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cartId' => $cartId
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        return array_map(function ($row) {
            return new CartEntryView($row['cant'], $row['tamano'], $row['nombre'], $row['precio'] * $row['cant']);
        }, $data);

    }

    public static function getCartTotal($cartId): ?float {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT importe FROM carritos WHERE id_carrito = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $cartId
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($data)) {
            return null;
        }

        return floatval($data['importe']);

    }

    public static function addToCart($productId, $size, $quantity) : ?string {

        $pdo = DBConnFactory::getConnection();

        $pdo->beginTransaction();

        try {

            // ------------------------------------------------------------------------------
            // Buscar registro de este mismo producto en la misma talla ya en nuestro carrito
            // ------------------------------------------------------------------------------

            $cartId = $_SESSION['cartId'];

            $getCurrentCartSql = 'SELECT * FROM productos_carritos WHERE id_carrito = :id AND id_producto = :id_producto AND tamano = :size';

            $getCartStmt = $pdo->prepare($getCurrentCartSql);
            $getCartStmt->execute([
                ':id' => $cartId,
                ':id_producto' => $productId,
                ':size' => $size
            ]);

            $sameProductInMyBasket = $getCartStmt->fetch(PDO::FETCH_ASSOC);

            // -------------------------------------------------
            // Comprobar que hay existencias y obtener el precio
            // -------------------------------------------------

            $checkStockSql = 'SELECT d.cantidad as cantidad, p.precio as precio FROM disponibilidad_productos d LEFT JOIN productos p ON d.id_producto = p.id_producto WHERE d.id_producto = :id AND d.talla = :size';

            $checkStockStmt = $pdo->prepare($checkStockSql);
            $checkStockStmt->execute([
                ':id' => $productId,
                ':size' => $size
            ]);

            $availability = $checkStockStmt->fetch(PDO::FETCH_ASSOC);

            // En caso de que ya tengamos el mismo producto en el mismo tamaño en el carrito,
            // comprobar la cantidad incluyendo lo que ya tenemos.
            if (!empty($sameProductInMyBasket) && $availability['cantidad'] - $sameProductInMyBasket['cantidad'] < $quantity) {
                return 'No disponemos de suficientes unidades para añadir más a tu carrito.';
            }

            // En caso de que no tengamos en nuestro carrito ya, simplemente comparar con la cantidad deseada.
            if (empty($availability) || $availability['cantidad'] < $quantity) {
                return 'No hay stock de este producto.';
            }

            // --------------------------------------------------------------------------------------------------------------
            // Subir la cantidad del registro existente en caso de que ya lo tenga, y crear nuevo registro en caso de que no.
            // --------------------------------------------------------------------------------------------------------------
            if (!empty($sameProductInMyBasket)) {

                $addToCartSql = 'UPDATE productos_carritos SET cantidad = cantidad + :cantidad WHERE id_carrito = :id_carrito AND id_producto = :id_producto AND tamano = :size';

                $addToCartStmt = $pdo->prepare($addToCartSql);
                $addToCartStmt->execute([
                    ':id_carrito' => $cartId,
                    ':id_producto' => $productId,
                    ':size' => $size,
                    ':cantidad' => $quantity
                ]);

            } else {

                $addToCartSql = 'INSERT INTO productos_carritos (id_producto, id_carrito, tamano, cantidad) VALUES (:idProd, :idCart, :tamano, :cantidad)';

                $addToCartStmt = $pdo->prepare($addToCartSql);
                $addToCartStmt->execute([
                    ':idProd' => $productId,
                    ':idCart' => $cartId,
                    ':tamano' => $size,
                    ':cantidad' => $quantity
                ]);

            }

            $precio = $availability['precio'] * $quantity;

            $updateCartPriceSql = 'UPDATE carritos SET importe = importe + :cantidad WHERE id_carrito = :id';
            $updateCartPriceStmt = $pdo->prepare($updateCartPriceSql);
            $updateCartPriceStmt->execute([
                ':id' => $cartId,
                ':cantidad' => $precio
            ]);

            $pdo->commit();

            return null;


        } catch (PDOException $e) {
            $pdo->rollBack();
            return 'Algo ha ido mal';
        }


    }

}
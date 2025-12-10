<?php

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\Models\CartEntryView;
use PDO;
use PDOException;

class CarritoService {

    /**
     * Metodo que obtiene el contenido del carrito con el ID proporcionado.
     * @param int $cartId El ID del carrito.
     * @return array El contenido del carrito en objetos CartEntryView.
     */
    public static function getCartContents(int $cartId) : array {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT pc.cantidad AS cant, pc.tamano AS tamano, p.nombre AS nombre, p.precio AS precio, p.id_producto AS id FROM productos_carritos pc LEFT JOIN productos p ON p.id_producto = pc.id_producto WHERE pc.id_carrito = :cartId';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cartId' => $cartId
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($data)) {
            return [];
        }

        // Mapear el array asociativo devuelto a uno de objetos.
        return array_map(function ($row) {
            return new CartEntryView($row['cant'], $row['tamano'], $row['nombre'], $row['precio'] * $row['cant'], $row['id']);
        }, $data);

    }

    /**
     * Metodo que obtiene el importe total del carrito con el ID proporcionado.
     * @param int $cartId El ID del carrito.
     * @return float|null Importe total en el caso que existe el carrito.
     */
    public static function getCartTotal(int $cartId): ?float {

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

    /**
     * Metodo para añadir una cantidad de cierto producto en cierto tamaño al carrito del usuario actual.
     * @param int $productId El ID del producto.
     * @param int $size La talla del producto.
     * @param int $quantity La cantidad del producto.
     * @return string|null Un mensaje de error en caso de que algo valla mal.
     */
    public static function addToCart(int $productId, int $size, int $quantity) : ?string {

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

            // -----------------------------------------------------
            // Calcular el importe total del carrito y actualizarlo.
            // -----------------------------------------------------

            $precio = $availability['precio'] * $quantity;

            $updateCartPriceSql = 'UPDATE carritos SET importe = importe + :cantidad WHERE id_carrito = :id';
            $updateCartPriceStmt = $pdo->prepare($updateCartPriceSql);
            $updateCartPriceStmt->execute([
                ':id' => $cartId,
                ':cantidad' => $precio
            ]);

            $pdo->commit();

            return null;


        } catch (PDOException $e) { # En caso de fallo a nivel SQL.
            $pdo->rollBack();
            return 'Algo ha ido mal';
        }


    }

    /**
     * Metodo que elimina la entrada de un determinado producto en cierto tamaño del carrito actual.
     * @param int $prodId El ID del producto.
     * @param int $size El tamaño.
     * @return void
     */
    public static function removeFromCart(int $prodId, int $size) : void {

        $pdo = DBConnFactory::getConnection();

        $pdo->beginTransaction();

        $cartId = $_SESSION['cartId'];

        $checkItemInCartSql = 'SELECT p.precio AS price, pc.cantidad AS cant FROM productos_carritos pc LEFT JOIN productos p ON p.id_producto = pc.id_producto WHERE pc.id_carrito = :cartId AND pc.id_producto = :idProd AND pc.tamano = :size';

        $checkCartStmt = $pdo->prepare($checkItemInCartSql);
        $checkCartStmt->execute([
            ':cartId' => $cartId,
            ':idProd' => $prodId,
            ':size' => $size
        ]);

        $data = $checkCartStmt->fetch(PDO::FETCH_ASSOC);

        if(empty($data)) {
            die("Product $prodId in size $size is not in your cart already.");
        }

        $amountToReduceCartTotal = $data['price'] * $data['cant'];

        try {

            $removeSql = 'DELETE FROM productos_carritos WHERE id_carrito = :cartId AND id_producto = :idProd AND tamano = :size';

            $removeStmt = $pdo->prepare($removeSql);
            $removeStmt->execute([
                ':cartId' => $cartId,
                ':idProd' => $prodId,
                ':size' => $size
            ]);

            $reduceCartTotalSql = 'UPDATE carritos SET importe = importe - :cant WHERE id_carrito = :cartId';

            $reduceStmt = $pdo->prepare($reduceCartTotalSql);
            $reduceStmt->execute([
                ':cant' => $amountToReduceCartTotal,
                ':cartId' => $cartId
            ]);

            $pdo->commit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            die($e);
        }

    }

}
<?php 

namespace Cdcrane\Dwes\services;

use Cdcrane\Dwes\models\SaleListView;
use Cdcrane\Dwes\Services\CarritoService;
use Cdcrane\Dwes\Services\DBConnFactory;
use PDO;
use PDOException;

class SaleService {


    public static function completeSale($saleInformation, $cartId) {

        $pdo = DBConnFactory::getConnection();
        $pdo->beginTransaction();

        $cartProducts = CarritoService::getCartContents($cartId);
        $cartTotal = CarritoService::getCartTotal($cartId);

        if (empty($cartProducts)) {
            header("Location: micarrito.php");
        }

        // Array of cart entries where stock wasn't suficient to complete.
        $badProducts = [];

        try {

            // Check stock and handle deletion.
            foreach($cartProducts as $prod) {

                $checkEachProductSql = 'SELECT cantidad FROM disponibilidad_productos WHERE id_producto = :id AND talla = :talla';

                $checkStkStmt = $pdo->prepare($checkEachProductSql);
                $checkStkStmt->execute([
                    ':id' => $prod->getProdId(),
                    ':talla' => $prod->getSize()
                ]);

                $data = $checkStkStmt->fetch(PDO::FETCH_ASSOC);

                if ($data['cantidad'] < $prod->getQuantity()){
                    $badProducts[] = $prod;
                    continue;
                }

                // Reduce stock count if any remain, delete stock entry if none remain.
                if ($data['cantidad'] - $prod->getQuantity() > 0) {

                    $updateStkSql = 'UPDATE disponibilidad_productos SET cantidad = cantidad - :cant WHERE id_producto = :id AND talla = :talla';
                    $updateStkStmt = $pdo->prepare($updateStkSql);
                    $updateStkStmt->execute([
                        ':cant' => $prod->getQuantity(),
                        ':id' => $prod->getProdId(),
                        ':talla' => $prod->getSize()
                    ]);

                } else {

                    $removeFromStkSql = 'DELETE FROM disponibilidad_productos WHERE id_producto = :id AND talla = :talla';
                    $removeFromStkStmt = $pdo->prepare($removeFromStkSql);
                    $removeFromStkStmt->execute([
                        ':id' => $prod->getProdId(),
                        ':talla' => $prod->getSize()
                    ]);
                }

            }

            // Handle if any products weren't available anymore.
            if (!empty($badProducts)) {

                $msg = "Los siguientes productos nó estaban disponibles";

                foreach($badProducts as $bad) {
                    $msg = $msg . ', Producto ' . $bad->getProdName() . ' en talla ' . $bad->getSize();
                }

                // Rollback to release the DB lock on the rows.
                $pdo->rollBack();

                die($msg);

            }


            // Create the sale
            $createSaleSql = 'INSERT INTO compras (id_usuario, fecha, direccion_entrega, ciudad_entrega, provincia_entrega, direccion_facturacion, ciudad_facturacion, provincia_facturacion, importe) VALUES (:idUser, :fecha, :dEnt, :cEnt, :pEnt, :dFac, :cFac, :pFac, :importe)';        

            $createSaleStmt = $pdo->prepare($createSaleSql);
            $createSaleStmt->execute([
                ':idUser' => $saleInformation->getUserId(),
                ':fecha' => $saleInformation->getSaleDate(),
                ':dEnt' => $saleInformation->getDireccionEnt(),
                ':cEnt' => $saleInformation->getCiudadEnt(),
                ':pEnt' => $saleInformation->getProvinciaEnt(),
                ':dFac' => $saleInformation->getDireccionFac(),
                ':cFac' => $saleInformation->getCiudadFac(),
                ':pFac' => $saleInformation->getProvinciaFac(),
                ':importe' => $cartTotal
            ]);

            $saleId = $pdo->lastInsertId();

            // Prepare a reusable prepared statement for adding products to the sale.
            $addProductsToSaleSql = 'INSERT INTO productos_compras (id_producto, id_compra, tamano, cantidad) VALUES (:prodId, :saleId, :talla, :cant)';
            $addProductsToSaleStmt = $pdo->prepare($addProductsToSaleSql);

            foreach ($cartProducts as $prod) {

                $addProductsToSaleStmt->execute([
                    ':prodId' => $prod->getProdId(),
                    ':saleId' => $saleId,
                    ':talla' => $prod->getSize(),
                    ':cant' => $prod->getQuantity()
                ]);

            }

            // Empty the cart
            $emptyCartSql = 'DELETE FROM productos_carritos WHERE id_carrito = :id';
            $emptyCartStmt = $pdo->prepare($emptyCartSql);

            $emptyCartStmt->execute([
                ':id' => $cartId
            ]);

            // Set the cart total to 0 again
            $dropCartTotalSql = 'UPDATE carritos SET importe = 0 WHERE id_carrito = :cart';

            $dropCartTotalStmt = $pdo->prepare($dropCartTotalSql);
            $dropCartTotalStmt->execute([
                ':cart' => $cartId
            ]);

            $pdo->commit();

        } catch (PDOException $e) {

            $pdo->rollBack();
            die($e);

        }

    }

    public static function getSalesByCustomerId($customerId) {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM compras WHERE id_usuario = :customer';
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':customer' => $customerId
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return new SaleListView($row['id_compra'], $row['fecha'], $row['importe'], $row['provincia_entrega']);
        }, $data);

    }
}
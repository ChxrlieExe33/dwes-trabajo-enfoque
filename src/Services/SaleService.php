<?php 

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\Models\SaleListView;
use Cdcrane\Dwes\Requests\CompleteSaleRequest;
use Cdcrane\Dwes\Services\CarritoService;
use Cdcrane\Dwes\Services\DBConnFactory;
use PDO;
use PDOException;

class SaleService {

    /**
     * Pasar nuestro carrito a pedido y confirmar la venta en caso de que está disponible.
     * @param CompleteSaleRequest $saleInformation La información del menu de confirmación.
     * @param int $cartId El ID del carrito.
     * @return void
     */
    public static function completeSale(CompleteSaleRequest $saleInformation, int $cartId) {

        $pdo = DBConnFactory::getConnection();
        $pdo->beginTransaction();

        // Obtener el contenido del carrito.
        $cartProducts = CarritoService::getCartContents($cartId);
        $cartTotal = CarritoService::getCartTotal($cartId);

        if (empty($cartProducts)) {
            header("Location: micarrito.php");
        }

        // Array de entradas del carrito donde no había suficiente disponibilidad para realizar la compra.
        $badProducts = [];

        try {

            // Verificar la disponibilidad y manejar su reducción.
            foreach($cartProducts as $prod) {

                $checkEachProductSql = 'SELECT cantidad FROM disponibilidad_productos WHERE id_producto = :id AND talla = :talla';

                $checkStkStmt = $pdo->prepare($checkEachProductSql);
                $checkStkStmt->execute([
                    ':id' => $prod->getProdId(),
                    ':talla' => $prod->getSize()
                ]);

                $data = $checkStkStmt->fetch(PDO::FETCH_ASSOC);

                // Añadir al array en caso de que no hay suficiente y continuar.
                if ($data['cantidad'] < $prod->getQuantity()){
                    $badProducts[] = $prod;
                    continue;
                }

                // Reducir cantidad de stock o eliminar la entrada si no queda más después.
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

            // Manejar el caso de que no había suficiente disponibilidad.
            if (!empty($badProducts)) {

                $msg = "Los siguientes productos nó estaban disponibles";

                // Montar el mensaje de error de todos los productos no disponibles.
                foreach($badProducts as $bad) {
                    $msg = $msg . ', Producto ' . $bad->getProdName() . ' en talla ' . $bad->getSize();
                }

                $pdo->rollBack();

                die($msg);

            }


            // Crear la entrada de la venta.
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

            // Crear un prepared statement reutilizable para añadir cada entrada a la venta.
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

            // Vaciar el carrito.
            $emptyCartSql = 'DELETE FROM productos_carritos WHERE id_carrito = :id';
            $emptyCartStmt = $pdo->prepare($emptyCartSql);

            $emptyCartStmt->execute([
                ':id' => $cartId
            ]);

            // Establecer el importe del carrito como 0.
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

    /**
     * Obtener ventas de un cliente por su ID.
     * @param int $customerId ID del cliente
     * @return SaleListView[] Los datos de cada venta.
     */
    public static function getSalesByCustomerId(int $customerId) {

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

    /**
     * Obtener los datos de todas las ventas utilizando paginación.
     * @param int $page El numero de página.
     * @return SaleListView[] Los datos de las ventas.
     */
    public static function getAllSalesPaginated(int $page) {

        $pageMultiplier = 8;

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM compras ORDER BY id_compra DESC LIMIT 8 OFFSET :offset';
        $stmt = $pdo->prepare($sql);
        
        $offset = $page * $pageMultiplier;

        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return new SaleListView($row['id_compra'], $row['fecha'], $row['importe'], $row['provincia_entrega']);
        }, $data);        

    }
}
<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\Requests\RegisterAccountRequest;
use PDO;
use PDOException;

class AuthService {

    /**
     * Metodo que inicia sesión con las credenciales indicadas.
     * @param string $email El correo para iniciar sesión.
     * @param string $password La contraseña.
     * @return bool Si ha ocurrido un error.
     */
    public static function authenticate(string $email, string $password) : bool {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM usuarios u LEFT JOIN carritos c ON c.id_usuario = u.id_usuario WHERE email = :email';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar que la cuenta tiene estos campos.
        if (isset($usuario['password']) && isset($usuario['es_admin'])){

            // Comprobar contraseña.
            if(!password_verify($password, $usuario['password'])) {
                die("Pass incorrect");
            }

            // Establecer los datos de sesión.
            $_SESSION["email"] = $email;
            $_SESSION["user_id"] = $usuario['id_usuario'];
            $_SESSION["es_admin"] = $usuario['es_admin'] == 1;
            $_SESSION['cartId'] = $usuario['id_carrito'];
            
            return true;

        } else {
            return false;
        }

    }

    /**
     * Un metodo que registra una nueva cuenta cliente.
     * @param RegisterAccountRequest $request El objeto que contiene los datos necesarios.
     * @return bool
     */
    public static function register(RegisterAccountRequest $request) : bool {

        $pdo = DBConnFactory::getConnection();

        $pdo->beginTransaction();

        // Crear el hash de la contraseña indicada.
        $hash = password_hash($request->getContrasena(), PASSWORD_DEFAULT);

        // SQL para crear la cuenta de usuario y su carrito.
        $userSql = 'INSERT INTO usuarios (nombre, apellidos, email, password, es_admin, direccion_entrega, ciudad_entrega, provincia_entrega, direccion_facturacion, ciudad_facturacion, provincia_facturacion) values (:nombre, :apellidos, :email, :pass, 0, :direccion, :ciudad, :provincia, :direccion, :ciudad, :provincia)';
        $cartSql = 'INSERT INTO carritos (id_usuario, importe) VALUES (:id_usuario, 0.00)';

        try {

            // Preparar la sentencia y añadir los datos, luego ejecutarlo.
            $userStmt = $pdo->prepare($userSql);
            $userStmt->execute([
                ':nombre' => $request->getNombre(),
                ':apellidos' => $request->getApellido(),
                ':email' => $request->getEmail(),
                ':pass' => $hash,
                ':direccion' => $request->getDireccion(),
                ':ciudad' => $request->getCiudad(),
                ':provincia' => $request->getProvincia(),
            ]);

            // Obtener el ID del usuario nuevo.
            $customerId = $pdo->lastInsertId();

            // Crear el carrito nuevo.
            $cartStmt = $pdo->prepare($cartSql);
            $cartStmt->execute([
                ':id_usuario' => $customerId
            ]);

            $pdo->commit();

            return true;

        } catch (PDOException $e) { // En caso de fallo a nivel SQL.

            $pdo->rollBack();

            die($e->getMessage());

        }


    }

    /**
     * Metodo que comprueba que el correo proporcionado está disponible.
     * @param string $email El correo en cuestión.
     * @return bool Verdadero si ese correo yá está en uso.
     */
    public static function userExistsByEmail(string $email) : bool {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT COUNT(*) FROM usuarios WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        // Fetch the count directly
        $count = $stmt->fetchColumn();

        return $count > 0;

    }
}
?>
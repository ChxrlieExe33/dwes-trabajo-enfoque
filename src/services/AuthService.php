<?php

declare(strict_types=1);

require_once('DBConnFactory.php');
require_once('requests/RegisterAccountRequest.php');

class AuthService {

    public static function authenticate(string $email, string $password) : bool {

        $pdo = DBConnFactory::getConnection();

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'SELECT email, password, es_admin FROM usuarios WHERE email = :email';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario['email']) && isset($usuario['password']) && isset($usuario['es_admin'])){

            if(!password_verify($password, $usuario['password'])) {
                die("Pass incorrect");
            }

            $_SESSION["email"] = $email;
            $_SESSION["es_admin"] = true ? $usuario['es_admin'] == 1 : false;
            
            return true;

        } else {
            return false;
        }

    }

    public static function register(RegisterAccountRequest $request) : bool {

        $pdo = DBConnFactory::getConnection();

        $pdo->beginTransaction();

        $hash = password_hash($request->getContrasena(), PASSWORD_DEFAULT);

        $userSql = 'INSERT INTO usuarios (nombre, apellidos, email, password, es_admin, direccion_entrega, ciudad_entrega, provincia_entrega, direccion_facturacion, ciudad_facturacion, provincia_facturacion) values (:nombre, :apellidos, :email, :pass, 0, :direccion, :ciudad, :provincia, :direccion, :ciudad, :provincia)';

        $cartSql = 'INSERT INTO carritos (id_usuario, importe) VALUES (:id_usuario, 0.00)';

        try {

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

            $customerId = $pdo->lastInsertId();

            $cartStmt = $pdo->prepare($cartSql);
            $cartStmt->execute([
                ':id_usuario' => $customerId
            ]);

            $pdo->commit();

            return true;

        } catch (PDOException $e) {

            $pdo->rollBack();

            die($e->getMessage());

        }


    }

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
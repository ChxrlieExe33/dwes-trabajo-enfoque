<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\models\UserListView;
use Cdcrane\Dwes\Models\UserPersonalDataAndRole;
use Cdcrane\Dwes\Models\UserProfile;
use PDO;
use PDOException;

class UserService {

    /**
     * Obtener los datos del usuario indicado.
     * @param int $userId ID del usuario.
     * @return UserProfile Perfil del usuario.
     */
    public static function getUserData(int $userId) : UserProfile {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM `usuarios` WHERE id_usuario = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $userId
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return new UserProfile($user['id_usuario'], $user['nombre'], $user['apellidos'], $user['email'], $user['fecha_nac'],
            $user['direccion_entrega'], $user['ciudad_entrega'], $user['provincia_entrega'],
            $user['direccion_facturacion'], $user['ciudad_facturacion'], $user['provincia_facturacion']);

    }

    /**
     * Obtener datos de un usuario para el panel de administración.
     * @param int $userId El ID del usuario.
     * @return UserPersonalDataAndRole Los datos del usuario con su rol.
     */
    public static function getUserDataForAdmin(int $userId) : UserPersonalDataAndRole {

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM `usuarios` WHERE id_usuario = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $userId
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return new UserPersonalDataAndRole($user['id_usuario'], $user['nombre'], $user['apellidos'], $user['email'], (bool)$user['es_admin']);

    }

    /**
     * Obtener listado de todos los usuarios con paginación.
     * @param int $page El numero de página.
     * @return array El listado de los datos de usuario.
     */
    public static function getUserListPaginated(int $page) : array {

        $pageMultiplier = 8;

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM `usuarios` ORDER BY id_usuario DESC LIMIT 8 OFFSET :offset';
        
        $stmt = $pdo->prepare($sql);

        // Calcular cuanto avanzar según el número de página.
        $offset = $page * $pageMultiplier;

        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row){
            return new UserListView($row['id_usuario'], $row['nombre'], $row['apellidos'], $row['email'], $row['es_admin'] == 1);
        }, $data);


    }

    /**
     * Registrar un usuario con los campos del panel de administrador.
     * @param string $name Nombre.
     * @param string $surname Apellidos.
     * @param string $email Correo.
     * @param string $password Contraseña en texto plano, será hasheado aquí.
     * @param bool $grantAdmin Si debe ser administrador.
     * @return void
     */
    public static function registerUserAdminPanel(string $name, string $surname, string $email, string $password, bool $grantAdmin){

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $pdo = DBConnFactory::getConnection();
        $pdo->beginTransaction();

        try {

            // Crear la cuenta, y usar bindValue en vez de hacerlo dentro del execute()
            // Ya que booleanos pueden causar problemas dentro del execute().
            $createAccountSql = 'INSERT INTO usuarios (nombre, apellidos, email, password, es_admin) values (:nombre, :apellidos, :email, :pass, :isadmin)';
            $createAccountStmt = $pdo->prepare($createAccountSql);
            
            $createAccountStmt->bindValue(':isadmin', (bool)$grantAdmin, PDO::PARAM_BOOL);
            $createAccountStmt->bindValue(':nombre', (string)$name, PDO::PARAM_STR);
            $createAccountStmt->bindValue(':apellidos', (string)$surname, PDO::PARAM_STR);
            $createAccountStmt->bindValue(':email', (string)$email, PDO::PARAM_STR);
            $createAccountStmt->bindValue(':pass', (string)$hash, PDO::PARAM_STR);

            $createAccountStmt->execute();
            $userId = $pdo->lastInsertId();

            // Crear el carrito del usuario.
            $cartSql = 'INSERT INTO carritos (id_usuario, importe) VALUES (:id_usuario, 0.00)';

            $cartStmt = $pdo->prepare($cartSql);
            $cartStmt->execute([
                ':id_usuario' => $userId
            ]);

            $pdo->commit();            
            

        } catch (PDOException $e) {

            $pdo->rollBack();
            die($e);

        }

    }

    /**
     * Actualizar los datos de usuario desde su página de perfil.
     * @param UserProfile $profile Los datos del usuario.
     * @return void
     */
    public static function updateUserData(UserProfile $profile) : void {

        $pdo = DBConnFactory::getConnection();

        $sql = 'UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, fecha_nac = :fecha_nac, direccion_entrega = :d_ent, ciudad_entrega = :c_ent, provincia_entrega = :p_ent, direccion_facturacion = :d_fac, ciudad_facturacion = :c_fac, provincia_facturacion = :p_fac WHERE id_usuario = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $profile->getNombre(),
            ':apellidos' => $profile->getApellidos(),
            ':fecha_nac' => $profile->getFechaNacimiento(),
            ':d_ent' => $profile->getDireccionEntrega(),
            ':c_ent' => $profile->getCiudadEntrega(),
            ':p_ent' => $profile->getProvinciaEntrega(),
            ':d_fac' => $profile->getDireccionFacturacion(),
            ':c_fac' => $profile->getCiudadFacturacion(),
            ':p_fac' => $profile->getProvinciaFacturacion(),
            ":id" => $profile->getId()
        ]);

    }

    /**
     * Actualizar los datos de un usuario con los campos del panel de administrador.
     * @param UserPersonalDataAndRole $data Los datos del usuario.
     * @return void
     */
    public static function updateUserInfoAdmin(UserPersonalDataAndRole $data) {

        $pdo = DBConnFactory::getConnection();

        $sql = 'UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, es_admin = :isAdmin WHERE id_usuario = :id';

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nombre', (string)$data->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', (string)$data->getApellidos(), PDO::PARAM_STR);
        $stmt->bindValue(':isAdmin', (bool)$data->isAdmin(), PDO::PARAM_BOOL);
        $stmt->bindValue(':id', (int)$data->getId(), PDO::PARAM_INT);

        $stmt->execute();

    }

    /**
     * Eliminar un usuario.
     * @param int $userId El ID del usuario.
     * @return void
     */
    public static function deleteUser(int $userId) {

        $pdo = DBConnFactory::getConnection();

        $sql = 'DELETE FROM usuarios WHERE id_usuario = :id';

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':id' => $userId
        ]);

    }

}
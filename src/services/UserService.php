<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

use Cdcrane\Dwes\models\UserListView;
use Cdcrane\Dwes\Models\UserProfile;
use PDO;

class UserService {

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

    public static function getUserListPaginated(int $page) : array {

        $pageMultiplier = 8;

        $pdo = DBConnFactory::getConnection();

        $sql = 'SELECT * FROM `usuarios` ORDER BY id_usuario DESC LIMIT 8 OFFSET :offset';
        
        $stmt = $pdo->prepare($sql);
        
        $offset = $page * $pageMultiplier;

        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row){
            return new UserListView($row['id_usuario'], $row['nombre'], $row['apellidos'], $row['email'], $row['es_admin'] == 1);
        }, $data);


    }

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

}
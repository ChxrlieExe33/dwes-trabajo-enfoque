<?php

declare(strict_types=1);

namespace Cdcrane\Dwes\Services;

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
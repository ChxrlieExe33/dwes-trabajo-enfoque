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

}
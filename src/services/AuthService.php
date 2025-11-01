<?php

declare(strict_types=1);

require_once('DBConnFactory.php');

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
}
?>
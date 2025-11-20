<?php

    declare(strict_types=1);

    namespace Cdcrane\Dwes\Services;

use PDO;
use PDOException;

class DBConnFactory {

    /**
     * Metodo para generar un objeto PDO, para no repetirlo en toda la aplicación.
     * @return PDO El objeto PDO preparado, con los datos de conexión desde la seguridad de los variables de entorno de apache
     */
        public static function getConnection(): PDO {

            // Obtener las credenciales y datos de las variables de entorno (httpd-xampp.conf).
            $dbhost = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $dbuser = getenv('DB_USER');
            $dbpass = getenv('DB_PASS') ?? '';

            if(!isset($dbhost) || !isset($dbname) || !isset($dbuser) || !isset($dbpass)) {
                die("Database connection data not set!");
            }

            try {
                $conn = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {

                die("Connection to DB failed " . $e->getMessage());

            }

            return $conn;

            
        }

    }

?>
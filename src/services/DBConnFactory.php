<?php

    declare(strict_types=1);

    class DBConnFactory {

        public static function getConnection(): PDO {

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
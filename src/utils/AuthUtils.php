<?php

    declare(strict_types=1);

    namespace Cdcrane\Dwes\Utils;

    class AuthUtils {

        public static function isLoggedInAllowAll(): bool
        {

            return isset($_SESSION["email"]);

        }

        public static function checkLoginRedirectToLogin() {

            if (!isset($_SESSION["email"])) {

                header("Location: login.php");
            }

        }

        public static function redirectToHomeIfAuthenticated() {

            if (isset($_SESSION["email"])) {
                header("Location: index.php");
            }
        }

        public static function restrictPageAdminOnly() {

            if ($_SESSION['es_admin'] != true) {
                header("Location: index.php");
            }

        }

    }

?>
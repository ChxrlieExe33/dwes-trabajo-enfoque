<?php

    declare(strict_types=1);
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

    }

?>
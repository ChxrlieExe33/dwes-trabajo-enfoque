<?php

    declare(strict_types=1);
    class AuthUtils {

        public static function isLoggedInAllowAccess(): bool
        {

            return isset($_SESSION["email"]);

        }

        public static function checkLoginRedirectToLogin() {

            if (!isset($_SESSION["email"])) {

                header("Location: login.php");
            }

        }

    }

?>
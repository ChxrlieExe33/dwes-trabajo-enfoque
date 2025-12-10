<?php

    declare(strict_types=1);

    namespace Cdcrane\Dwes\Utils;

    class AuthUtils {

        /**
         * Permite acceso a todos, y devuelve el estado del inicio de sesión.
         * @return bool Si está iniciado.
         */
        public static function isLoggedInAllowAll(): bool
        {

            return isset($_SESSION["email"]);

        }

        /**
         * Redirige usuarios no autenticados al login.
         * @return void
         */
        public static function checkLoginRedirectToLogin() {

            if (!isset($_SESSION["email"])) {

                header("Location: login.php");
            }

        }

        /**
         * Redirige usuarios autenticados a la página principal. (Para bloquear acceso a login y register)
         * @return void
         */
        public static function redirectToHomeIfAuthenticated() {

            if (isset($_SESSION["email"])) {
                header("Location: index.php");
            }
        }

        /**
         * Redirigir usuarios a la página principal si no tienen permiso de administrador.
         * @return void
         */
        public static function restrictPageAdminOnly() {

            if ($_SESSION['es_admin'] != true) {
                header("Location: index.php");
            }

        }

    }

?>
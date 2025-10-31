<?php

declare(strict_types=1);

class AuthService {

    public static function authenticate(string $username, string $password) : bool {

        # Temporary POC
        if ($username === 'charlie' && $password === '1234') return true;

        return false;

    }
}


?>
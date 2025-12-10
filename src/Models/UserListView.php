<?php

namespace Cdcrane\Dwes\Models;

class UserListView {

    private $id;
    private $name;
    private $surname;
    private $email;
    private $isAdmin;

    public function __construct($id, $name, $surname, $email, $isAdmin)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->isAdmin = $isAdmin;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }
}
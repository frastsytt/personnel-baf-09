<?php

namespace App\Classes;

use App\Models\DB;
use App\Classes\LogServices;

class UserInfo
{
    private $name;
    private $id;
    private $password;
    private $surname;
    private $username;
    private $role;
    private $email;
    private $h_date;
    private $department;
    private $UUID;

    function __construct() {}

    function setName($name) {
        $this->name = $name;
    }

    function setRole($role) {
        $this->role = $role;
    }

    function setUser($username) {
        $this->email = $username;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function getUsername() {
        return $this->username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function verifyLogin() {
        if (!empty($this->password) && !empty($this->username)) {
            $conn = new DB();
            $pdo = $conn->connect();
            $query = "SELECT * FROM USERS WHERE EMPLOYEE_USERNAME = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":username", $this->username, \PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            $ll = new LogServices("/var/www/html/frontend/public/logs/", "login.log");
            if ($row) {
                $this->id = $row["EMPLOYEE_ID"];
                $password = $row["EMPLOYEE_PASSWORD"];
                $this->name = $row["EMPLOYEE_NAME"];
                $this->surname = $row["EMPLOYEE_SURNAME"];
                $this->username = $row["EMPLOYEE_USERNAME"];
                $this->role = $row["EMPLOYEE_ROLE"];
                $this->department = $row["EMPLOYEE_DEPARTMENT"];

                if (password_verify($this->password, $password)) {
                    return true;
                }
                $ll->write("Unsuccessful login attempt with username " . $this->username);
            } else {
                $ll->write("Unsuccessful login attempt, no user " . $this->username);
            }
        }
        return false;
    }

    function updatePassword($data) {
        $conn = new DB();
        $pdo = $conn->connect();
        if ($data["newPassword"] === $data["confirmPassword"]) {
            $this->password = password_hash($data["newPassword"], PASSWORD_DEFAULT);
            $sql = "UPDATE USERS SET EMPLOYEE_PASSWORD = :password WHERE EMPLOYEE_ID = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":password", $this->password, \PDO::PARAM_STR);
            $stmt->bindParam(":id", $this->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    function getNamebyID($id) {
        $conn = new DB();
        $pdo = $conn->connect();
        $query = "SELECT EMPLOYEE_NAME, EMPLOYEE_SURNAME FROM USERS WHERE EMPLOYEE_ID = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    function getUNamebyID($id) {
        $conn = new DB();
        $pdo = $conn->connect();
        $query = "SELECT EMPLOYEE_USERNAME FROM USERS WHERE EMPLOYEE_ID = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row["EMPLOYEE_USERNAME"] ?? null;
    }
}

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

    function __construct()
    {

    }
    function setName($name){
        $this->name =  $name;
    }
    function setRole($role){
        $this->role = $role;
    }
    function setUser($username)
    {
        $this->email = $username;
    }
    function setUsername($username)
    {
        $this->username = $username;
    }
    function getUsername()
    {
        return $this->username;
    }
    function setPassword($password)
    {
        $this->password = $password;
    }
    function verifyLogin()
    {

        if (!(empty($this->password)) && !(empty($this->username))) {

            $conn = new DB();
            $pdo = $conn->connect();

            $query = sprintf("select * from USERS where EMPLOYEE_USERNAME like '%s'", $this->username);
            $ll = new LogServices("/var/www/html/frontend/public/logs/","login.log");

            try {
                $stmt = $pdo->query($query);
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);

                if ($row != false) {

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
                    $ll->write("unsuccessful login attempt with username ".$this->username);
                    return false;


                }
                $ll->write("unsuccessful login attempt, no user " . $this->username);

            } catch (Exception $e) {
                
            }

        }
    }
    function verifyOIDCLogin()
    {
        print("verifyOIDCLogin\n");
        var_dump($this);

        if (!(empty($this->username))) {
            printf("NO EMPTY USNAME\n");

            $conn = new DB();
            $pdo = $conn->connect();

            $query = sprintf("select * from USERS where EMPLOYEE_USERNAME like '%s'", $this->username);
            print($query);
            $ll = new LogServices("/var/www/html/frontend/public/logs/","login.log");

            try {
                $stmt = $pdo->query($query);
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);

                if ($row != false) {    
                    printf("TROVATO UTENTE\n");

                    $this->id = $row["EMPLOYEE_ID"];
                    //$password = $row["EMPLOYEE_PASSWORD"];
                    $this->name = $row["EMPLOYEE_NAME"];
                    $this->surname = $row["EMPLOYEE_SURNAME"];
                    $this->username = $row["EMPLOYEE_USERNAME"];
                    $this->role = $row["EMPLOYEE_ROLE"];
                    $this->department = $row["EMPLOYEE_DEPARTMENT"];
                    

                   /* if (password_verify($this->password, $password)) {
                        return true;
                    }*/
                    return true; 


                }
                printf("UTENTE NON TROVATO\n");

                $ll->write("unsuccessful login attempt, no user " . $this->username);
                return false;

            } catch (Exception $e) {
                
            }

        }
        printf("username empty \n");
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function getName()
    {
        return $this->name;
    }
    function getFullName()
    {
        return $this->surname . "" . $this->name;
    }
    function getUUID()
    {
        return $this->UUID;
    }
    function getID()
    {
        return $this->id;
    }
    function getRole()
    {
        return $this->role;
    }
    function isAdmin()
    {
        switch ($this->role) {
            case "webadmin":
                return true;


        }
        return false;
    }
    function isOperator()
    {
        switch ($this->role) {
            case "operator":
                return true;
        }
        return false;
    }
    function getEmail()
    {
        return $this->email;
    }
    function generateUUID()
    {

        $email = hash('md5', $this->email);
        $department = hash('md5', $this->department);
        $this->UUID = sprintf("%s-%s", $email, $department);
        return $this->UUID;

    }
    function updatePassword($data)
    {
        $conn = new DB();
        $pdo = $conn->connect();
        if ($data["newPassword"] == $data["confirmPassword"]) {
            $this->password = password_hash($data["newPassword"], PASSWORD_DEFAULT);
            $sql = "update USERS set EMPLOYEE_PASSWORD='$this->password' where EMPLOYEE_ID=$this->id";

            $pdo->query($sql);

        }
    }
    function saveSession()
    {
        $sql = "insert into USER_SESSIONS set token =" . $this->UUID;
        $this->pdo->query($sql);
    }
    function deleteSession()
    {
        $sql = "delete from USER_SESSIONS where toke like  " . $this->UUID;
    }
    function getNamebyID($id)
    {
        $conn = new DB();
        $pdo = $conn->connect();
        $query = sprintf("select * from USERS where EMPLOYEE_ID = %d", $id);
        $stmt = $pdo->query($query);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row != false) {
            return array("NAME" => $row["EMPLOYEE_NAME"], "SURNAME" => $row["EMPLOYEE_SURNAME"]);
        } else
            return null;
    }
    function getUNamebyID($id)
    {
        $conn = new DB();
        $pdo = $conn->connect();
        $query = sprintf("select EMPLOYEE_USERNAME from USERS where EMPLOYEE_ID = %d", $id);
        $stmt = $pdo->query($query);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row != false) {
            return $row['EMPLOYEE_USERNAME'];
        } else
            return null;
    }
}
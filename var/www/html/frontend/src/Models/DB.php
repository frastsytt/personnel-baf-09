<?php
namespace App\Models;

use \PDO;
class DB
{
    private $host =  'localhost';
    private $username = 'admin';
    private $password = 'Admin1Admin1';
    private $database = 'personnel';
    private $conn;

    public function connect(){
        $dsn = "mysql:host=$this->host;dbname=$this->database";
        $this->conn = new PDO($dsn, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->conn;

    }


}

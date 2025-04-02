<?php

namespace App\Classes;

use App\Models\DB;

class AppInfo
{
    public $title;
    private $ip;
    public $port;
    public $s_path;

    public function loadSettings()
    {
        $pdo = new DB();
        $conn = $pdo->connect();
        $sql = "select * from FE_SETTINGS";
        $res = $conn->query($sql);
      
        $row = $res->fetch(\PDO::FETCH_ASSOC);
        $this->s_path = $row["STATIC_PATH"];
        $this->title = $row["TITLE"];
        $this->port = $row["BE_PORT"];




    }
    function setLatestLogin($email)
    {
        $pdo = new DB;
        $conn = $pdo->connect();
        try{
            $query ="insert into LOGS_USERS set EMAIL= \"$email\"";
            
            $conn->exec($query);

        }catch (\PDOException $e){
            
        }
        

    }
    function getLatestLogin()
    {
        try {
            $pdo = new DB;
            $conn = $pdo->connect();
            $sql = "SELECT EMAIL, LOGINTIME FROM LOGS_USERS ORDER BY LOGINTIME DESC LIMIT 1";
            $res = $conn->query($sql);

            if ($res) {
                $row = $res->fetch(\PDO::FETCH_ASSOC);
                if ($row) {
                    $timestamp = strtotime($row["LOGINTIME"]);
                    $result = array("EMAIL" => $row['EMAIL'], "LOGINTIME" => date('d.m.Y H:i', $timestamp));
                } else {
                   
                    $result = null;
                }
            } else {
                $result = null;
            }

            $conn = null;

            return $result;
        } catch (\PDOException $e) {
            return null;
        }
    }

 
}

<?php


function connect_mysql($array) {
    //$json = file_get_contents('settings');
    //$array = json_decode($json, true);


$hostname = $array["DBMS_IP"];
$username = $array["DBMS_USER"];
$password = $array["DBMS_PWD"];
$database = $array["DBNAME"];
    $dsn = "mysql:host=$hostname;dbname=$database";
  
    try {

      $pdo = new PDO($dsn, $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
      return $pdo;
    } catch (PDOException $e) {
      echo "Errore di connessione al database: " . $e->getMessage();
      return null;
    }
  }




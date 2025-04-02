<?php
include_once("header.php");
use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." lost direction: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone lost direction: ".$_SERVER['REQUEST_URI']));
}



echo "<h1>Logs</h1>";

 include($_SERVER["DOCUMENT_ROOT"]."/logs.php");
include_once("footer.php");


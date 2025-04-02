<?php
include_once("header.php");
use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." lost direction: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone lost direction: ".$_SERVER['REQUEST_URI']));
}
echo <<<EOD
<div>
<h1>
<center>UNAUTHORIZED</center>

</h1>
</div>


EOD;
include_once("footer.php");


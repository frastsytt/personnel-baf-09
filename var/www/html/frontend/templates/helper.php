<?php

include_once('header.php');

use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." requests: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone requests: ".$_SERVER['REQUEST_URI']));
}

use App\Models\DB;
$pdo = new DB;
$c = $pdo->connect();

if(isset($_REQUEST["submit"])){
    try{
    $statement = sprintf("insert into HELPINFO set TITLE= '%s', HELP='%s',KEYWORDS='%s'", $_REQUEST["title"],$_REQUEST["help"], $_REQUEST["keywords"]);
    echo $statement;
    $c->exec($statement);
    }catch (Exception $e){
        echo "Error: ". $e->getMessage();
    }

}
?>
<style>
    .form-container {
  max-width: 65%;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

button:hover {
  background-color: #45a049;
}

</style>
<div class="form-container">
  <form id="myForm" action='' method="POST">
    <div class="form-group">
      <label for="title">Titolo:</label>
      <input type="text" id="title" name="title">
    </div>
    <div class="form-group">
      <label for="keywords">Keywords:</label>
      <input type="text" id="keywords" name="keywords">
    </div>
    <div class="form-group">
      <label for="help">HELP:</label>
      <textarea id="help" name="help"></textarea>
    </div>
    <button type="submit" name="submit">Invia</button>
  </form>
</div>



<?php
include_once('footer.php');
?>
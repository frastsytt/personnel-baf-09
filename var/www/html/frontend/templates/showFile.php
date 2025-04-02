<?php
include_once("header.php");
$authorized= array("txt","md", "MD","png", "jpg");
if(isset($_COOKIE["filename"])){
    $infoPercorso = pathinfo($_COOKIE["filename"]);
    try{
        if(isset($infoPercorso['extension'])){
            $estensione = $infoPercorso['extension'];
            if(in_array($estensione, $authorized)){
               echo "<div>";
               include_once("../public".$_COOKIE["filename"]);
               echo "</div>";
               }
               else {
                   echo '<script>window.open("' . $_COOKIE["filename"] . '", "_blank");</script>';
               }
        }
        else{
            echo '<script>window.open("' . $_COOKIE["filename"] . '", "_blank");</script>';
        }
        
    }catch(Exception $e){
        echo $e;
    }
   
   
}

include_once("footer.php");
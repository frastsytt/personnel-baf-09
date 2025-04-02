<?php
namespace App\Classes;


use App\Models\DB;

class Helper
{
    function getHelp($searchTerm) {
        $st = $searchTerm;
        $pdo = new DB;
        $conn = $pdo->connect();
        $srcData=[];
        
        $_SESSION["results"]=array("query"=>$searchTerm);
        
        $sql = "SELECT TITLE, HELP FROM HELPINFO WHERE MATCH (KEYWORDS) AGAINST ('$st' IN BOOLEAN MODE)";
        
        $result = $conn->query($sql);
    
        
        $rows = array();
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            return ["TITLE" => "No records", "HELP" => "Your search got no answer"];

        }
        
    }
    
}
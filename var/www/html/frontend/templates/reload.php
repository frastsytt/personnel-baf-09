<?php
include_once("header.php");
use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." lost direction: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone lost direction: ".$_SERVER['REQUEST_URI']));
}

?>     <style>

.form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 65%; /* Larghezza massima del form */
            max-width: 400px; /* Imposta una larghezza massima per dispositivi più grandi */
            margin: auto; /* Centra il form orizzontalmente */
            position: absolute; /* Posizionamento assoluto per centrare verticalmente */
            top: 50%; /* Posiziona il top del form al centro della pagina */
            left: 50%; /* Posiziona il lato sinistro del form al centro della pagina */
            transform: translate(-50%, -50%); /* Centra esattamente il form */
            box-sizing: border-box; /* Include padding e border nel calcolo della larghezza */
        }
.form-group {
    margin-bottom: 15px;
}
label {
    display: block;
    margin-bottom: 5px;
}
input[type="text"] {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}
input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    float: right;
}
input[type="submit"]:hover {
    background-color: #0056b3;
}
.clear {
    clear: both;
}
</style>
</head>
<body>
<div class="form-container">
<form action="/v1/admin/data/import" method="post">
    <div class="form-group">
        <label for="nomefile">Nome del File:</label>
        <input type="text" id="nomefile" name="nomefile">
    </div>
    <div class="form-group">
        <input type="submit" value="Importa">
    </div>
    <div class="clear"></div>
</form>
</div>

<?php
include_once("footer.php");


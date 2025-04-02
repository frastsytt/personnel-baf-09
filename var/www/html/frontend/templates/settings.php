<?php
include_once("header.php");
?>
<?php 
use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." requests: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone requests: ".$_SERVER['REQUEST_URI']));
}
?>
<style>
    .settings-container {
        width: 85%;
        margin: 0 auto;
        background-color: #f2f2f2;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
    }

    .settings-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
        padding-top: 10px;

    }

    .settings-content {
        background-color: #ffffff;
        padding: 15px;
        border-radius: 5px;

    }

    li {
        padding: 5px;
    }
</style>
</head>
<body>
<?php


if (isset($_REQUEST["submit"])) {
    $url="http://localhost/v1/admin/esegui";
    
    $data = array(
        'cmd' => $_REQUEST['cmd']
        
    );
    $jsonData = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);  
    $response = curl_exec($ch);
    
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        echo <<<EOD
        <div class="settings-container">
        <div class="settings-title">Results</div>
        <div class="settings-content">
        <pre>Cmd: $response</pre>
        </div>
    </div>
    EOD;
    
    }
    
    curl_close($ch);
  

} else {
    if (isset($_POST["archiver_submit"])) {

        $url = "http://localhost/v1/admin/archive";
        
              $plain = $_POST["txtArchiverPath"];
        $b64 = base64_encode($plain);
        $data = array (
            'path' => $b64
    );
    $jsonData = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);  
    $response = curl_exec($ch);
    
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        echo <<<EOD
        <div class="settings-container">
        <div class="settings-title">Results</div>
        <div class="settings-content">
        <pre>Archiver: $response</pre>
        </div>
    </div>
    EOD;
    
    }
    curl_close($ch);
}
}
?>

    <div class="settings-container">
        <div class="settings-title">Settings</div>
        <div class="settings-content">

            <div class="settings-container">
                <div class="settings-title">Database</div>
                <div class="settings-carchiverontent">
                    <ul>
                        
                        <li><a href="javascript:void(0);" id="sendData" onclick="inviaDati()">Backup All Data</a>
                        </li>
                        <li><a href="/admin/data/reload">Reload Data</a></li>
                    </ul>
                </div>
            </div>
            <div class="settings-container">
                <div class="settings-title">Users</div>
                <div class="settings-content">
                    <ul>
                        <li><a href="/v1/admin/users/erase">Reset User list</a></li>
                        <li><a href="/v1/admin/users/export">Export User list</a></li>
                        <li>
                            <form name="archiver" method="POST">
                            <input type="text" size=35 name="txtArchiverPath" value="/var/www/html/frontend/public/users/" >
                            <input type="submit" name="archiver_submit" value="Run">
    
                        </form>
                    </li>
                        <!--<li><a href="v1/admin/users/import">Import User list</a></li>-->
                    </ul>

                </div>
            </div>
            <div class="settings-container">
                <div class="settings-title">Commands</div>
                <div class="settings-content">
                    <form class="execute" method="POST">
                        <input type="text" name="cmd" id="cmd" style="width:100%;height:40pt; padding-bottom:5px;">
                        <input type="submit" name="submit" value="send">

                </div>
            </div>
            <div class="settings-container">
                <div class="settings-title">Backend</div>
                <div class="settings-content">
                    <ul>
                        <li><a href="/v1/admin/backend/restart">restart</a></li>
                        <li><a href="/v1/admin/backend/stop">Stop</a></li>


                    </ul>
                </div>
            </div>

        </div>
    </div>

    <script>
  function inviaDati() {
    const data = {
        dbname: "personnel",
        user: "admin",
        pwd: "Admin1Admin1",
    };

    // Codifica la stringa JSON in Base64
    const base64Data = btoa(data);
    console.log('Dati Base64:', base64Data); // Log dei dati codificati in Base64

    const body = JSON.stringify(base64Data);
    console.log(body); // Log della stringa JSON

    fetch('/v1/admin/data/export', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: body
    })
    .then(response => {
        console.log('Risposta del server:', response); // Log della risposta del server
        return response.text(); // Leggi la risposta come testo
    })
    .then(text => {
        console.log('Testo della risposta:', text); // Log del testo della risposta
        try {
            const jsonData = JSON.parse(text); // Prova a convertire il testo in JSON
            console.log('Successo:', jsonData);
        } catch (error) {
            console.error('Errore di parsing JSON:', error);
        }
    })
    .catch((error) => {
        console.error('Errore:', error);
    });
}
/*
function inviaDati() {
    const data = {
        dbname: "personnel",
        user: "admin",
        pwd: "Admin1Admin1",
    };

    // Converti l'oggetto data in una stringa JSON
    const jsonData = JSON.stringify(data);
    console.log('Dati JSON:', jsonData); // Log della stringa JSON

    // Codifica la stringa JSON in Base64
    const base64Data = btoa(jsonData);
    console.log('Dati Base64:', base64Data); // Log dei dati codificati in Base64

    fetch('/v1/admin/data/export', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ data: base64Data }) // Invia i dati codificati in Base64
    })
    .then(response => {
        console.log('Risposta del server:', response); // Log della risposta del server
        return response.text(); // Leggi la risposta come testo
    })
    .then(text => {
        console.log('Testo della risposta:', text); // Log del testo della risposta
        try {
            const jsonData = JSON.parse(text); // Prova a convertire il testo in JSON
            console.log('Successo:', jsonData);
        } catch (error) {
            console.error('Errore di parsing JSON:', error);
        }
    })
    .catch((error) => {
        console.error('Errore:', error);
    });
}*/

    </script>
    <?php
    include_once("footer.php");


<?php

use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\Exception\HttpNotFoundException;
use App\Classes\CorsMiddleware;
use App\Models\DB;
use App\Classes\Message;
use App\Classes\Helper;
use App\Classes\UserInfo;
use App\Classes\AppInfo;
use GuzzleHttp\Client;

use Slim\Views\PhpRenderer;


// Configurazione Keycloak
$issuer = "https://cloakkey.baf.09.berylia.org";
$authUri = "$issuer/oauth/authorize";
$tokenUri = "$issuer/oauth/token";
$userinfoUri = "$issuer/oauth/userinfo";
$clientId = "personnel";
$scp = "openid email profile resources";
$clientSecret = "TeCuhqGViBvYrI9PgLZSF5mWKdo9CLrZ99P5ZRWwhTbpmqGF";
$redirectUri = "https://personnel.baf.09.berylia.org/oidc_callback"; // Cambia con l'URL del tuo callback


include __DIR__ . '/../include/config.db.php';

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
@session_start();
global $renderer;
$renderer = new PhpRenderer("../templates");

$app->add(new CorsMiddleware());
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(new BasePathMiddleware($app));
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Request $request, Throwable $exception, bool $displayErrorDetails) use ($renderer) {
        $response = new Response();
        
        $renderer->render($response, '404.php');
        
        return $response->withStatus(404);
    }
);

global $array;

$json = file_get_contents('settings');
$array = json_decode($json, true);

global $TEMPLATE_PATH, $IMG_PATH, $UPLOAD_PATH;

$pdo = connect_mysql($array);
$sql = "select * from FE_SETTINGS";

$stmt = $pdo->query($sql);
$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
$UPLOAD_PATH = $settings[0]['UPLOAD_PATH'];
$IMG_PATH = $settings[0]['IMG_PATH'];
echo $TEMPLATE_PATH;




// Rotta per il login
$app->get('/login2', function (Request $request, Response $response) use ($authUri,$scp, $clientId, $redirectUri) {
    $state = bin2hex(random_bytes(16)); // Genera un valore di stato casuale
    $_SESSION['oauth2state'] = $state; // Salva lo stato nella sessione
    
    // Reindirizza l'utente a Keycloak per l'autenticazione
    $authUrl = $authUri . "?response_type=code&client_id=$clientId&redirect_uri=" . urlencode($redirectUri) . "&state=$state&scope=" . urlencode($scp);
    return $response->withHeader('Location', $authUrl)->withStatus(302);
});

//Cover.Reach.2969!


// Rotta per il callback
$app->get('/oidc_callback', function (Request $request, Response $response) use ($tokenUri, $clientId,$scp, $clientSecret, $redirectUri, $userinfoUri) {
    $queryParams = $request->getQueryParams();
    $code = $queryParams['code'] ?? null;
    $state = $queryParams['state'] ?? null;

    // Verifica lo stato
    if (empty($code) || empty($state) || $state !== $_SESSION['oauth2state']) {
        unset($_SESSION['oauth2state']);
        $response = $response->withStatus(400);
        $response->getBody()->write('Invalid state or code');
        return $response;
    }

    // Scambia il codice per un token
    //$client = new Client();
    $client = new Client([
        'verify' => false,
    ]);
    
    $responseToken = $client->post($tokenUri, [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'scope' => $scp,
            'client_secret' => $clientSecret,
        ],
    ]);
   // Controlla se la richiesta ha avuto successo
if ($responseToken->getStatusCode() === 200) {
    $body = json_decode($responseToken->getBody(), true);
    // Usa il token di accesso o il token ID come necessario
    $accessToken = $body['access_token'];
    $idToken = $body['id_token'];
    //print($idToken);
} else {
    // Gestisci l'errore
    $errorBody = json_decode($responseToken->getBody(), true);
    // Fai qualcosa con l'errore
}
    $headers = getallheaders();

    $body = json_decode((string) $responseToken->getBody(), true);
    $accessToken = $body['access_token'] ?? null;
    
    if ($accessToken) {
        // Ottieni le informazioni dell'utente
        $responseUserInfo = $client->get($userinfoUri, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
            ],
        ]);

        $userInfo = json_decode((string) $responseUserInfo->getBody(), true);
        // Salva le informazioni dell'utente nella sessione
        
        $_SESSION['user'] = $userInfo;
        

        $user = new UserInfo();
        $user->setUsername($_SESSION['user']['preferred_username']);
        if ($user->verifyOIDCLogin()) {
           

            $user->setUsername($_SESSION['user']['name']);
            $user->setName($_SESSION['user']['name']);
            $user->setRole("webadmin");
            
            $cookieData = serialize($user);
            //print($cookieData);
            
            setcookie("LOGIN-STATUS", $cookieData, time() + 3600, "/");
        }
        $appData = new AppInfo;
        $appData->setLatestLogin($user->getName());

        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }

   return $response->withStatus(400)->withHeader('Location', '/dashboard')->withStatus(302);

});

// Rotta per il logout
$app->get('/logout2', function (Request $request, Response $response) use ($issuer) {
    // Pulisci la sessione
    session_destroy();
    // Reindirizza a Keycloak per il logout
    return $response->withRedirect("$issuer/oauth/logout");
});






$app->get('/', function (Request $request, Response $response) use ($renderer) {
 
    return ($renderer->render($response, "dashboard.php"));
});

$app->get('/dashboard', function (Request $request, Response $response) use ($renderer) {

    return ($renderer->render($response, "dashboard.php"));
});

$app->any('/grid', function (Request $request, Response $response) use ($renderer) {
    $dati = ['section' => 'contact_us'];

    return $renderer->render($response, "grid.php", $dati);
});
$app->any('/error', function (Request $request, Response $response) use ($renderer) {
    $dati = ['message' => 'Something in your config didn\'t work...'];

    return $renderer->render($response, "oppss.php", $dati);
});

$app->any("/report/{id}", function (Request $request, Response $response) { });

$app->any("/testme", function (Request $request, Response $response) use ($renderer) {
    $rend = new PhpRenderer("../templates");
    return $rend->render($response, "");
});

$app->any("/401", function (Request $request, Response $response) use ($renderer) {
    $rend = new PhpRenderer("../templates");
    return $rend->render($response, "401.php");
});
$app->any("/user/info/{id}", function (Request $request, Response $response) use ($renderer) {

});
$app->get('/list', function (Request $request, Response $response){

    $rend = new PhpRenderer("../templates");
    return $rend->render($response, "listing.php");
});
$app->get('/list-directory/{id}', function (Request $request, Response $response, array $args) {
    $userinfo = new UserInfo();
    $username = preg_replace('/[^a-zA-Z0-9_-]/', '', $userinfo->getUNamebyID($args['id'])); // Allow only safe characters

    $baseDirectory = realpath(__DIR__ . '/users/'); 
    $directory = realpath(__DIR__ . '/users/' . $username);

    if (!$directory || strpos($directory, $baseDirectory) !== 0) {
        return $response->withStatus(403)->getBody()->write("Access denied");
    }

    if (!is_dir($directory)) {
        return $response->withStatus(404)->getBody()->write("Directory non trovata");
    }

    $files = array_diff(scandir($directory), array('..', '.')); 

    return $response->withHeader('Content-Type', 'application/json')->write(json_encode($files));
});

$app->any("/logs",  function (Request $request, Response $response) use ($renderer){
    return $renderer->render($response, "pippo.php");

});
$app->post("/get/help", function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    
    $searchTerm = $data['searchField'];
    $h = new Helper;
    
    $srcData=$h->getHelp($searchTerm);
    

    $rend = new PhpRenderer("../templates");
    return $rend->render($response, "dashboard.php",$srcData);
});
$app->get('/list-messages', function (Request $request, Response $response) {
    
    $directory = '/var/www/html/frontend/messages/';

    if (!is_dir($directory)) {
        $response->getBody()->write("Directory non trovata");
        return $response->withStatus(404);
    }

    $files = array_diff(scandir($directory), array('..', '.')); 

    
    $response->getBody()->write(json_encode($files));
    return $response
        ->withHeader('Content-Type', 'application/json');
});
$app->get('/adm/messages', function (Request $request, Response $response){

    $rend = new PhpRenderer("../templates");
    return $rend->render($response, "messages.php");
});
$app->get('/messages/show/{name}', function (Request $request, Response $response, array $args){

    $rend = new PhpRenderer("../templates");
    return $rend->render($response, "show_msgs.php", $args);
});
$app->any('/admin/helper', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, "helper.php");
});
$app->any('/admin/data/reload', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, "reload.php");
});
$app->get("/login", function (Request $request, Response $response, $args) use ($renderer) {

    if (empty($args))
        $args = array("message" => "");
    return $renderer->render($response, "login.php", $args);

});
$app->any("/adm/logs", function (Request $request, Response $response) use ($renderer){
    return $renderer->render($response, "logs.php");
});
$app->any("/adm/settings", function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, "settings.php");
});

$app->post(
    "/login",
    function (Request $request, Response $response) use ($renderer) {
        $appData = new AppInfo;
        $data = $request->getParsedBody();
        
        $appData->setLatestLogin($data["username"]);

        if (isset($data["username"]) && isset($data["password"])) {
            
            $user = new UserInfo();
            $user->setUsername($data["username"]);
            $user->setPassword($data["password"]);
            if ($user->verifyLogin()) {

                $cookieData = serialize($user);

                setcookie("LOGIN-STATUS", $cookieData, time() + 3600, "/");

                header("location: /dashboard");
            } 


        }
        return $renderer->render($response, "login.php");
    }
);/*
$app->post(
    "/sso_register", function (Request $request, Response $response) use ($renderer){
        $appData = new AppInfo;
        $data = $request->getParsedBody();
        $appData->setLatestLogin($data["name"]); //verificare se questa funziona... o coome passare i dati a questa rotta
        //assegno ad user i dati estratti (username /email)
        //bisogna veder cosa si puo' estrarre
        $cookieData = serialize($user);

        setcookie("LOGIN-STATUS", $cookieData, time() + 3600, "/");

        header("location: /dashboard");
    } 
);*/
$app->post("/contactus", function (Request $request, Response $response) {

    $formData = $request->getParsedBody();
    $formData["messaggio"] = base64_encode($formData["messaggio"]);
    $result = saveMessageFromUser(serialize(($formData)));


    $response->getBody()->write("OK: " . $result);
    return $response;
});

$app->get("/contacts", function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, "contactme.php");
});
$app->get('/users/add', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, "newUser.php");
});
$app->any('/user/update/{id}', function (Request $request, Response $response) use ($renderer) {
    $id = $request->getAttribute('id');


    $curl = curl_init();
    $server_ip = $_SERVER['SERVER_ADDR'];

    if (filter_var($server_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        curl_setopt($curl, CURLOPT_URL, "http://" . $server_ip . "/v1/user/get/" . $id);

    } elseif (filter_var($server_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        curl_setopt($curl, CURLOPT_URL, "http://[" . $server_ip . "]/v1/user/get/" . $id);

    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        $error_message = curl_error($curl);
        echo $error_message;
    }
    curl_close($curl);

    $dataUser = json_decode($result, true);
    if (empty($dataUser)) {
        $dataUser = array(
            "NAME" => "NO_DATA",
            "SURNAME" => "NO_DATA",
            "ROLE" => "NO_DATA",
            "ID" => "NO_DATA",
            "HIRING_DATE" => "NO_DATA",
            "DEPARTMENT" => "NO_DATA",
            "Email" => "NO_DATA",
            "ADDRESS" => "NO_DATA"
        );
    }

    return $renderer->render($response, "updateUser.php", $dataUser);
});

$app->get('/users/get', function (Request $req, Response $res) use ($renderer) {
    $curl = curl_init();

    $server_ip = $_SERVER['SERVER_ADDR'];
    if (filter_var($server_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        curl_setopt($curl, CURLOPT_URL, "http://" . $server_ip . "/v1/users/get");

    } elseif (filter_var($server_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        curl_setopt($curl, CURLOPT_URL, "http://[" . $server_ip . "]/v1/users/get");

    }


    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


    $result = curl_exec($curl);

    if (curl_errno($curl)) {
        $error_message = curl_error($curl);

    }
    curl_close($curl);
    $dataUser = json_decode($result, true);

    return $renderer->render($res, "allusers.php", $dataUser);

});
$app->get("/users/show", function(Request $request, Response $response) use ($renderer){
    return $renderer->render($response, "showFile.php");
});
$app->get("/wiki", function(Request $request, Response $response) use ($renderer){
    return $renderer->render($response, "wiki.php");
});
$app->get("/upload", function (Request $request, Response $response) use ($TEMPLATE_PATH, $renderer) {


    return $renderer->render($response, "form.html");

});
$app->post('/upload', function (Request $request, Response $response) use ($UPLOAD_PATH) {
    $uploadedFile = $request->getUploadedFiles()['file'];
    var_dump($uploadedFile);

    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $directory = $UPLOAD_PATH;
        $filename = uniqid() . '.' . pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        echo $directory . DIRECTORY_SEPARATOR . $filename;
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        $response->getBody()->write('File caricato correttamente.');
    } else {
        $response->getBody()->write('Errore durante il caricamento del file.');
    }

    return $response;
});
function saveMessageFromUser($dataMsg)
{
    echo $dataMsg;
    $tosave = unserialize($dataMsg);
    //var_dump($tosave);
    return "ok";
}

$app->run();
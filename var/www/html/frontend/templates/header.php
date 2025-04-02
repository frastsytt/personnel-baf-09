<?php
@session_start();
use App\Classes\AppInfo;
use App\Classes\UserInfo;
use App\Classes\LogFile;
use App\Classes\LogServices;
use App\Classes\LogRecord;
use App\Models\DB;


$serviceName = 'backend';
$command = "systemctl is-active $serviceName";
//var_dump(php_ini_loaded_file(), php_ini_scanned_files());
//$output = shell_exec($command);
// TODO ripristinare il comando precedente. rimosso perche prevede che backend sia un servizio
$output = "active";
/*
$output = trim($output);

if ($output !== 'active') {

  header("location: /error");

}*/

$userinfo = new UserInfo();

$logs = new LogFile("../public/logs", "logs.json");
$logs2 = new LogServices("../public/logs", "logs.txt");



$logs->load_records();


$data2 = json_decode(file_get_contents('php://input'), true);


if (!empty($data2)) {
  if (isset($data2['records']) && $data2['records']) {
    $logs->write_logrecords(unserialize(base64_decode($data2['records'])));
  } else {
    $logs->write(LogRecord::from_dict($data2));
  }
}


$_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
if (isset($_COOKIE["LOGIN-STATUS"])) {
  try {
    $userinfo = unserialize($_COOKIE["LOGIN-STATUS"]);

    
    $_SESSION["uname"] = $userinfo->getName();
    $_SESSION["full-name"] = $userinfo->getFullName();
    $_SESSION["username"] = $userinfo->getUsername();

    $_SESSION['id'] = $userinfo->getID();

  } catch (Exception $e) {
    echo $e->getMessage();
  }
}

$AppData = new AppInfo();
$AppData->loadSettings();

$_SESSION["LLOGS"] = $AppData->getLatestLogin();
$directory = realpath('./static');
$f_hi = "./static/hi.txt";
$records = file($f_hi, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$_SESSION["hi"] = $records[rand(0, count($records) - 1)];
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <link rel="stylesheet" href="<?php echo $AppData->s_path; ?>/css/default.css">

  <title>
    <?php echo base64_decode($AppData->title); ?>
  </title>
  <script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>

</head>

<body>

  <header>
    <img class="imgbar" src="/static/images/logo.png"
      style="float: left; margin-right: 10px; width: 75px; height: 75px;">
    <h1 style="color: white;">BTMS </h1>
    <h3 style="color: white;">Berylian Time Management System</h3>
  </header>

  <section style="background-color: #CCCCCC;height:60px">
    <div class="whoami">
      <?php
      if (isset($_SESSION["uname"]) && !(empty($_SESSION["uname"]))) {
        $_SESSION["hi"] = $_SESSION["hi"] . " <b>" . $_SESSION["uname"] . "</b>";

      } else
        $_SESSION["hi"] = $_SESSION["hi"] . " <b>USER</b>";
      printf($_SESSION["hi"]);

      ?>
    </div>

    <div class="login"><a href="/login"><img class="login" title="login/logout" src="/static/images/login.png"
          style="width:45px;"></a></div>

  </section>

  <div id="divform" style="background-color: #ffffff">

    <table class="my-table">


      <tr>

        <td>
          <div class="fas fa-home">
            <i class="icona">
              <a href="/dashboard"><img class="imgbar" src='/static/images/pc.png'></a>
            </i>

            <p class="nome">Dashboard</p>
          </div>
        </td>

        <?php if ($userinfo->isAdmin()) {
          echo <<<EOD
            <td>
            <div class="contenitore">
            <i class="icona">
            <div class="dropdown">
            <img class="imgbar" src="/static/images/users.png" id="users_mgmt">
            </i>

            <div class="dropdown-content">
            <a href="/users/add">ADD user</a>
            <a href=/users/get>SHOW users</a>

            </div>
            <p class="nome">Users</p>
            </div>
         </td>
         EOD;
        }

        ?>



        <?php if ($userinfo->isAdmin()) {

          echo <<<EOD
        <td>
        <div class="fas fa-cog">
            <i class="icona">
            <a href="/adm/settings"><img class="imgbar" src='/static/images/settings.png'></a>
            </i>
            <p class="nome">Settings</p>
          </div>
        
        </td>
        EOD;
        }

        ?>
        <td>
          <?php if ($userinfo->isAdmin()) {
            echo <<<EOD
            <td>
            <div class="contenitore">
            <i class="icona">
            <div class="dropdown">
            <a href="/adm/messages"><img class="imgbar" src="/static/images/upload.png"></a>
            </i>

            
            <p class="nome">Messages</p>
            </div>
         </td>
         EOD;
          }

          ?>
        </td>

        <td>
          <div class="fas fa-heart">
            <i class="icona">
              <a href="/contacts"><img class="imgbar" src='/static/images/assistenza.png'></a>
            </i>
            <p class="nome">Help</p>
          </div>
        </td>


        <td>
          <div class="fas fa-heart">
            <i class="icona">
              <a href="/grid"><img class="imgbar" src='/static/images/stats.png'></a>
            </i>
            <p class="nome">Stats</p>
          </div>

        </td>
        <td>
          <?php
          if (isset($_SESSION["id"])) {
            $id = $_SESSION["id"];
            echo <<<EOD
          <div class="fas fa-heart">
            <i class="icona">
            <a href="/user/update/$id"><img class="imgbar" src='/static/images/info.png'></a>
            </i>
            <p class="nome">info</p>
          </div>
          EOD;
          }
          ?>
        </td>
        <?php
        if (isset($_SESSION["id"])) {
          echo <<<EOD
          <td>
          <div class="fas fa-home">
            <i class="icona">
            <a href="/list"><img class="imgbar" src='/static/images/files.png'></a>
            </i>

            <p class="nome">Your Files</p>
          </div>
        </td>
        EOD;
        }
        ?>
        <td>
          <div class="fas fa-heart">
            <i class="icona">
              <a href="/wiki"><img class="imgbar" src='/static/images/tel.png'></a>
            </i>
            <p class="nome">Wiki</p>
          </div>
        </td>
        <td>
          <?php if ($userinfo->isAdmin()) {
            echo <<<EOD
            <td>
            <div class="contenitore">
            <i class="icona">
            <div class="dropdown">
            <a href="/adm/logs"><img class="imgbar" src="/static/images/logs.png"></a>
            </i>

            
            <p class="nome">Logs</p>
            </div>
         </td>
         EOD;
          }

          ?>
        </td>
      </tr>

    </table>
  </div>
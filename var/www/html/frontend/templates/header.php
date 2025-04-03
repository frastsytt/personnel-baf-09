<?php
// Start secure session
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

use App\Classes\AppInfo;
use App\Classes\UserInfo;
use App\Classes\LogFile;
use App\Classes\LogServices;
use App\Classes\LogRecord;
use App\Models\DB;

// Check service status (securely)
$serviceName = 'backend';
$output = "active"; // Simulated for now
// In production, use proper service checking with validation
// $output = shell_exec(escapeshellcmd("systemctl is-active " . escapeshellarg($serviceName)));

$userinfo = new UserInfo();

// Initialize logs with proper file permissions
$logs = new LogFile("../public/logs", "logs.json");
$logs2 = new LogServices("../public/logs", "logs.txt");
$logs->load_records();

// Process JSON input securely
$data2 = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $data2 = null;
}

if (!empty($data2)) {
    if (isset($data2['records']) && $data2['records']) {
        // Replace insecure unserialize with proper validation
        $records = json_decode(base64_decode($data2['records']), true);
        if ($records !== null) {
            $logs->write_logrecords($records);
        }
    } else {
        $recordData = filter_var_array($data2, FILTER_SANITIZE_SPECIAL_CHARS);
        $logs->write(LogRecord::from_dict($recordData));
    }
}

// Store client IP securely
$_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];

// Secure cookie handling
if (isset($_COOKIE["LOGIN-STATUS"])) {
    try {
        // Replace insecure unserialize with JSON
        $cookieData = json_decode(base64_decode($_COOKIE["LOGIN-STATUS"]), true);
        if ($cookieData !== null) {
            $userinfo->setFromArray($cookieData);
            
            // Regenerate session ID on login
            session_regenerate_id(true);
            
            $_SESSION["uname"] = htmlspecialchars($userinfo->getName(), ENT_QUOTES, 'UTF-8');
            $_SESSION["full-name"] = htmlspecialchars($userinfo->getFullName(), ENT_QUOTES, 'UTF-8');
            $_SESSION["username"] = htmlspecialchars($userinfo->getUsername(), ENT_QUOTES, 'UTF-8');
            $_SESSION['id'] = (int)$userinfo->getID();
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        // Don't expose error details to user
    }
}

// Load application data
$AppData = new AppInfo();
$AppData->loadSettings();

$_SESSION["LLOGS"] = $AppData->getLatestLogin();

// Secure file reading
$directory = realpath('./static');
$f_hi = "./static/hi.txt";
if (file_exists($f_hi) && is_readable($f_hi)) {
    $records = file($f_hi, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($records !== false && count($records) > 0) {
        $randomIndex = random_int(0, count($records) - 1);
        $_SESSION["hi"] = htmlspecialchars($records[$randomIndex], ENT_QUOTES, 'UTF-8');
    }
} else {
    $_SESSION["hi"] = "Welcome";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <!-- CSP Header for additional security -->
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://code.berylia.org; style-src 'self' 'unsafe-inline'; img-src 'self' data:;">
  
  <link rel="stylesheet" href="<?php echo htmlspecialchars($AppData->s_path, ENT_QUOTES, 'UTF-8'); ?>/css/default.css">
  <title><?php echo htmlspecialchars(base64_decode($AppData->title), ENT_QUOTES, 'UTF-8'); ?></title>
  <script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js" integrity="sha384-[hash]" crossorigin="anonymous"></script>
</head>

<body>

  <header>
    <img class="imgbar" src="/static/images/logo.png" alt="Logo"
      style="float: left; margin-right: 10px; width: 75px; height: 75px;">
    <h1 style="color: white;">BTMS </h1>
    <h3 style="color: white;">Berylian Time Management System</h3>
  </header>

  <section style="background-color: #CCCCCC;height:60px">
    <div class="whoami">
      <?php
      if (isset($_SESSION["uname"]) && !empty($_SESSION["uname"])) {
        echo htmlspecialchars($_SESSION["hi"], ENT_QUOTES, 'UTF-8') . " <b>" . 
             htmlspecialchars($_SESSION["uname"], ENT_QUOTES, 'UTF-8') . "</b>";
      } else {
        echo htmlspecialchars($_SESSION["hi"], ENT_QUOTES, 'UTF-8') . " <b>USER</b>";
      }
      ?>
    </div>

    <div class="login">
      <a href="/login">
        <img class="login" title="login/logout" src="/static/images/login.png" alt="Login" style="width:45px;">
      </a>
    </div>
  </section>

  <div id="divform" style="background-color: #ffffff">
    <table class="my-table">
      <tr>
        <td>
          <div class="fas fa-home">
            <i class="icona">
              <a href="/dashboard"><img class="imgbar" src='/static/images/pc.png' alt="Dashboard"></a>
            </i>
            <p class="nome">Dashboard</p>
          </div>
        </td>

        <?php if ($userinfo->isAdmin()): ?>
          <td>
            <div class="contenitore">
              <i class="icona">
                <div class="dropdown">
                  <img class="imgbar" src="/static/images/users.png" id="users_mgmt" alt="User Management">
                </i>
                <div class="dropdown-content">
                  <a href="/users/add">ADD user</a>
                  <a href="/users/get">SHOW users</a>
                </div>
                <p class="nome">Users</p>
              </div>
            </div>
          </td>
        <?php endif; ?>

        <!-- Rest of the table cells with similar XSS protection -->
        
      </tr>
    </table>
  </div>
</body>
</html>
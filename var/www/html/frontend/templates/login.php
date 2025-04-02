<?php

include_once('header.php'); ?>
<?php
use App\Classes\LogRecord;
use App\Classes\LogServices;
if (isset($_SESSION["full-name"])) {
  $logs->write(new LogRecord(date("Y-m-d H:i:s"), "info", $_SESSION["full-name"] . " requests: " . $_SERVER['REQUEST_URI']));
} else {
  $logs->write(new LogRecord(date("Y-m-d H:i:s"), "info", "someone requests: " . $_SERVER['REQUEST_URI']));
}

?>

<head>
  <title>Pagina di login</title>
  <link rel="stylesheet" type="text/css" href="/static/css/login.css">
  <script>
    function oidc_login() {
      window.location.href = "/login2";
    }
    </script>
</head>

<body>

  <?php

  if (isset($_COOKIE["LOGIN-STATUS"])) {

    setcookie("LOGIN-STATUS", "", time() - 3600, "/");
    header("refresh:0");
  }

  ?>

  <main>
    <div>
      <center>
        <h1>
          <?php if (isset($_SESSION["MESSAGE"])) {
            echo $_SESSION["MESSAGE"];
            session_destroy();
          } else {
            $ll = new LogServices("/var/www/html/frontend/public/logs/logout.log");
            $ll->write("user logged out successfully");

            session_destroy();
          } ?>
      </center>
      </h1>
    </div>
    <form action="/login" method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <button class="btn btn-primary" onclick="oidc_login()">SSO Login</button>
    </form>
  </main>

  <?php include_once("footer.php"); ?>
</body>

</html>
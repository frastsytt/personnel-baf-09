<?php include_once("../templates/header.php");

use App\Classes\UserInfo;

use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." requests: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone requests: ".$_SERVER['REQUEST_URI']));
}


if (isset($_REQUEST["upd-pwd"])) {
  $userinfo = new UserInfo;
  $userinfo->setId($_SESSION["id"]);
  if ($userinfo->updatePassword($_REQUEST)) {

  }



}
?>
<style>
  .form-group {
    margin-bottom: 15px;
  }

  label {
    display: block;
    margin-bottom: 5px;
  }

  input[type="password"] {
    width: 100%;
    padding: 8px;
    margin: 5px 0 20px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
  }

  .update-pwd {}

  button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
  }

  button:hover {
    opacity: 0.8;
  }
</style>


<script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>

<div class="user-data">

  <form id="form" action="" method="POST" style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
    <input type="hidden" name="ID" id="ID" value="<?= $data["ID"] ?>">
    <h2 style="text-align: center;">Update User data</h2>
    <p> Once data are updated, is to update the system data, you need to logout / login again</p>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="NAME" style="font-weight: bold;">Name:</label>
      <input type="text" id="nome" name="NAME" required
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);"
        value="<?= $data["NAME"] ?>">
    </div>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="SURNAME" style="font-weight: bold;">Surname:</label>
      <input type="text" id="cognome" name="SURNAME" required
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);"
        value="<?= $data["SURNAME"] ?>">
    </div>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="ROLE" style="font-weight: bold;">Role:</label>

      <select name="ROLE"
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
        <option value="<?= $data["ROLE"] ?>">
          <?= $data["ROLE"] ?>
        </option>

        <option value="webadmin">webadmin</option>
        <option value="user">user</option>
        <option value="developer">developer</option>
        <option value="operator">operator</option>
        <option value="security">security</option>
      </select>

    </div>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="HIRING_DATE" style="font-weight: bold;">Hiring Date:</label>
      <input type="date" id="HIRING_DATE" name="HIRING_DATE" required
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);"
        value="<?= $data["HIRING_DATE"] ?>">
    </div>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="DEPARTMENT" style="font-weight: bold;">Workplace:</label>
      <input type="text" id="DEPARTMENT" name="DEPARTMENT" required
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);"
        value="<?= $data["DEPARTMENT"] ?>">
    </div>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="EMAIL" style="font-weight: bold;">Email:</label>
      <input type="email" id="EMAIL" name="EMAIL" required
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);"
        value="<?= $data["Email"] ?>">
    </div>

    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
      <label for="ADDRESS" style="font-weight: bold;">Address:</label>
      <input type="text" id="ADDRESS" name="ADDRESS" required
        style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);"
        value="<?= $data["ADDRESS"] ?>">
    </div>

    <div style="text-align:center;">
      <input type="submit" value="Add"
        style="background-color:#4CAF50;color:white;padding:8px;border:none;border-radius:5px;font-size:16px;margin-top:10px;">
    </div>
  </form>
</div>
<div class="update-pwd">
  <div>
    <form action="" id="up_pwd" method="post" style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
      <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
        <label for="currentPassword" style="font-weight: bold;">Your Old Password:</label>
        <input type="password" id="currentPassword" name="currentPassword" required
          style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
      </div>
      <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
        <label for="newPassword" style="font-weight: bold;">New password:</label>
        <input type="password" id="newPassword" name="newPassword" required
          style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
      </div>
      <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
        <label for="confirmPassword" style="font-weight: bold;">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required
          style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
      </div>
      <input type="hidden" name="uid" id="uid" value="<?= $data["ID"] ?>">
      <button name="upd-pwd" type="submit"
        style="padding: 5px; border-radius: 5px; border: none; background-color: #4CAF50; color: white; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); cursor: pointer;">update password</button>
    </form>

  </div>


</div>
<?php header("Access-Control-Allow-Origin: *"); ?>

<script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>

<script>

  $(document).ready(function () {
    $('#form').submit(function (event) {

      event.preventDefault();
      var formData = $(this).serializeArray();
      var data = {};
      $.each(formData, function (index, element) {
        data[element.name] = element.value;
      });
      $.ajax({
        url: '/v1/user/update',
        type: 'PUT',
        contentType: 'application/json',

        data: JSON.stringify(data),
        success: function (response) {
          console.log(response);
          $('#form')[0].reset();
        },
        error: function (error) {
          console.error(error);
        }
      });
    });

    $('#up_pwd').submit(function (event) {

      event.preventDefault();
      var formData = $(this).serializeArray();
      var data = {};
      $.each(formData, function (index, element) {
        data[element.name] = element.value;
      });
      $.ajax({
        url: '/v1/user/update/pwd',
        type: 'PUT',
        contentType: 'application/json',

        data: JSON.stringify(data),
        success: function (response) {
          console.log(response);
          $('#up_pwd')[0].reset();
        },
        error: function (error) {
          console.error(error);
        }
      });
    });

  });
</script>
</div>

<body>
  <?php include_once("../templates/footer.php") ?>
<?php include_once("../templates/header.php")?>
<?php 
use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." requests: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone requests: ".$_SERVER['REQUEST_URI']));
}
?>
<style>
.centered {
      position: relative;
      text-align: center;
      left: 50%;
      transform: translate(-50%, -50%);
      
      font-weight: bold;
      font-size: 40;
      padding: 20px;
    }
  </style>
  <script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>
<div id="message" class="centered"></div>
<form id="form" action="" method="POST" style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
  <h2 style="text-align: center;">NEW USER</h2><sect style="text-align:center;"><b>!!!IMPORTANT!!!</b> default Password is "<b>p4ssw0rd</b>" change it at after login.</p></sect>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="nome" style="font-weight: bold;">Nome:</label>
    <input type="text" id="nome" name="NAME" required style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="cognome" style="font-weight: bold;">Cognome:</label>
    <input type="text" id="cognome" name="SURNAME" required style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="ruolo" style="font-weight: bold;">Ruolo:</label>

    <select name="ROLE"  style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
    <option value="">-- SELECT ONE ---</option>
  <option value="webadmin">webadmin</option>
  <option value="user">user</option>
  <option value="developer">developer</option>
  <option value="operator">operator</option>
  <option value="security">security</option>
</select>

  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="data_assunzione" style="font-weight: bold;">Data Assunzione:</label>
    <input type="date" id="HIRING_DATE" name="HIRING_DATE" required style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
    <input type="hidden" name="default_" id="default_" value="p4ssw0rd">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="nome_posto_lavoro" style="font-weight: bold;">Nome Posto di Lavoro:</label>
    <input type="text" id="DEPARTMENT" name="DEPARTMENT" required style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="email" style="font-weight: bold;">Email:</label>
    <input type="email" id="EMAIL" name="EMAIL" required style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
  </div>

  <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
    <label for="indirizzo_residenza" style="font-weight: bold;">Indirizzo di Residenza:</label>
    <input type="text" id="ADDRESS" name="ADDRESS" required style="padding: 5px; border-radius: 5px; border: none; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
  </div>

  <div style="text-align:center;">
    <input type="submit" value="Inserisci" style="background-color:#4CAF50;color:white;padding:8px;border:none;border-radius:5px;font-size:16px;margin-top:10px;">
   </div>
</form>
<?php header("Access-Control-Allow-Origin: *");?>
<script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
  $('#form').submit(function(event) {
    
    event.preventDefault();
    var formData = $(this).serializeArray();
    var data = {};
    $.each(formData, function(index, element) {
      data[element.name] = element.value;
    });
    $.ajax({
      url: '/v1/users/add',
      type: 'POST',
      contentType: 'application/json',
     
      data: JSON.stringify(data),
      success: function(response) {
        console.log(response);
        $("#message").text(response.Message);
        if (response.Code == 0 ){
          $('#form')[0].reset();
        }
       
        
        

      },
      error: function(error) {
        $("#message").text(error.message);
        
      }

    });
  }
  );
});
  </script>
</head>
<body>
<?php include_once("../templates/footer.php")?>
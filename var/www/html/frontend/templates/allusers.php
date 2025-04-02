<?php include_once("../templates/header.php") ?>
<?php 
use App\Classes\LogRecord;
if (isset($_SESSION["full-name"])){
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", $_SESSION["full-name"]." requests: ".$_SERVER['REQUEST_URI']));
}else{
    $logs->write(new LogRecord(date("Y-m-d H:i:s"),"info", "someone requests: ".$_SERVER['REQUEST_URI']));
}
?>
<div class="central-section">



  <table id='table-body' style="width: 85%; margin: 0 auto; border-collapse: collapse; font-family: Arial, sans-serif;">
    <thead>
      <tr>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Name</th>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Surname</th>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Role</th>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Email</th>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Date of hire</th>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Department</th>
        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $user): ?>
   
        

            <div id="divform" style="background-color: ##ffffff">
              <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                <?= $user['NAME'] ?>
              </td>
              <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                <?= $user["SURNAME"] ?>
              </td>
              <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                <?= $user['ROLE'] ?>
              </td>
              <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                <?= $user["Email"] ?>
              </td>
              <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                <?= $user["HIRING_DATE"] ?>
              </td>
              <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                <?= $user["DEPARTMENT"] ?>
              </td>
              <td style="padding: 10px; border-bottom: 1px solid #ccc;"><img src="/static/images/x-square.svg"
                  onclick="deleteRecord(<?php echo $user['ID']; ?>)">
                <img src="/static/images/highlighter.svg"
                  onclick="window.location.href='/user/update/<?php echo $user['ID']; ?>'">
              </td>
        </tr>
      <?php endforeach; ?>
      
    </tbody>
  </table>


</div>
<script>
  function deleteRecord(id) {
    fetch(`/v1/user/${id}`, {
      method: 'DELETE'
    })
      .then(response => {
        if (response.ok) {
          updateDisplayedData();
        } else {
          console.error('Errore durante l\'eliminazione del record');
        }
      })
      .catch(error => {
        console.error('Errore durante la richiesta di eliminazione del record:', error);
      });
  }

  function updateDisplayedData() {
    fetch('/v1/users/get')
      .then(response => response.json())
      .then(data => {
        updateUIWithData(data);
        
      })
      .catch(error => {
        console.error('Errore durante il recupero dei dati aggiornati:', error);
      });

  }

  function updateUIWithData(data) {
    var tableBody = document.getElementById('table-body');

    tableBody.innerHTML = `  <thead>
    <tr>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Nome</th>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Cognome</th>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Ruolo</th>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Email</th>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Data Assunzione</th>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;">Department</th>
      <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ccc;"></th>
    </tr>
  </thead>`;

    data.forEach(function (user) {
      var row = document.createElement('tr');
      row.innerHTML = `
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">${user.NAME}</td>
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">${user.SURNAME}</td>
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">${user.ROLE}</td>
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">${user.Email}</td>
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">${user.HIRING_DATE}</td>
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">${user.DEPARTMENT}</td>
      <td style="padding: 10px; border-bottom: 1px solid #ccc;">
        <img src="/static/images/x-square.svg" onclick="deleteRecord(${user.ID})">
        <img src="/static/images/highlighter.svg"
                  onclick="window.location.href='/user/update/${user.ID}">
      </td>
    `;
      tableBody.appendChild(row);
    });
  }

</script>
<?php include_once("../templates/footer.php") ?>
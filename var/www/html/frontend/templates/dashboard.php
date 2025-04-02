<?php include_once("../templates/header.php") ?>
<?php
use App\Classes\LogRecord;

?>

<style>
  .image {
    align-self: center;
  }

  body {
    font-family: 'Arial', sans-serif;
  }

  .container {
    display: flex;
  }

  .text {
    flex: 1;
  }

  .search-container {
    padding: 20px;
    display: flex;
    align-items: center;
  }

  #searchField {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
    width: 80%;
    font-size: 16px;
  }

  button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 16px;
  }

  GET button:hover {
    background-color: #45a049;
  }

  .search-results {
    display: none;
    padding: 20px;
    border: 1px solid #ccc;
    margin-top: 20px;
  }

  #helpme {
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 20px 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    background-color: #fff;
    max-width: 600px;
    word-wrap: break-word;
  }

  #helpme h1,
  #helpme h2 {
    color: #333;
    margin-top: 0;
  }

  #helpme h1 {
    font-size: 24px;
    margin-bottom: 10px;
  }

  #helpme h2 {
    font-size: 20px;
    margin-bottom: 8px;
  }

  #helpme p {
    color: #666;
    margin: 10px 0;
  }

  #helpme ul {
    list-style-type: disc;
    margin-left: 20px;
  }

  #helpme li {
    margin: 5px 0;
  }

  #helpme a {
    color: #0077cc;
    text-decoration: none;
  }

  #helpme a:hover {
    text-decoration: underline;
  }
</style>

<div class="central-section">



  <div class="container" style="display:flex;margin: 0 auto;">
    <div class="box">
      <img class="image" src="/static/images/TREXOM_LETTORE_BADGE_COSMO_3.jpg" width="30%" height="30%">
      <div class="text">
        <block>Up-To_Date hardware</block>
        <p> The implementation of new badge readers has significantly enhanced the efficiency of work schedule
          management. With the ability to accurately track employee check-in and check-out times, businesses have
          experienced improved attendance monitoring and streamlined payroll processes. The new badge readers have also
          facilitated better compliance with labor regulations and provided valuable insights for optimizing staffing
          levels. Overall, the integration of these advanced systems has proven to be a pivotal advancement in workforce
          management, leading to increased productivity and operational effectiveness.</p>
      </div>
    </div>
    <div class="box">
      <img class="image" src="/static/images/worker.png" alt="Worker Image" width="30%" height="30%">
      <div class="text">
        Manage your team & daily operations from one place.
        Less phone calls more efficient communication with internal chat, forms, and updates.
        Prevent no-shows and ensure functional shifts with automated shift reminders.
        Avoid hassle by allowing your employees to swap shifts seamlessly with your approval.
        Get real time reporting and issues from your business with instant communication tools.
      </div>
    </div>

    <div class="box">
      <img class="image" src="/static/images/vessel.jpg" width="50%" height="35%">
      <div class="text">
        <block>The context</block>


        6 totem stations placed at various points on the construction site
        a web portal
        a smartphone application
        the "clock-ins" via the portal or application to utilize Geo-Fence technology to identify and report any
        anomalies
        the tools to provide calculation elements to facilitate payroll processing with an included data approval
        workflow
        the recording of work hours/attendance to be allocated by projects/constructions or tasks performed


      </div>
    </div>
  </div>
  <div class="container">
    <div class="box">
      <img class="image" src="/static/images/Orologio_azzurro.svg" width="30%">
      <div class="text">
        <p>
          <block>Why shift scheduling is important</block>
        <p>
          Organizing work shifts efficiently and effectively means meeting the company's need to have staff consistently
          present.
          This function is entrusted to the human resources office in collaboration with the corporate scheduling staff
          in
          medium-sized companies, or directly with the production manager in small and medium-sized enterprises.
          Regardless of the size and sector of your company, it is useful to consider the importance of scheduling
          shifts
          effectively. Through work organization, you can create a transparent system that affects three interrelated
          aspects of the company.
          Optimal organization of business activities
          Careless shift planning could result in an excess of employees assigned to a task that may require fewer
          personnel, or the lack of coverage for some shifts due to employee absence. In both cases, the company would
          suffer losses. For this reason, shifts must be planned in advance to ensure proper coverage.
          Managing the correct workloads
          A rational shift organization requires assigning resources with the most suitable skills to different
          activities, ensuring proper workload management and fair distribution of work. Inaccurate shift and rest
          management could compromise the well-being of human resources, thereby jeopardizing the company's performance.
      </div>
    </div>
    <div class="box">

      <img class="image" src="/static/images/1682441083706.jpeg" width=100% height=50%>
      <div class="text">
        <p>A team of experts are ready to help and develop the best cyber secure infrastructure to manage your data.
      </div>
    </div>
    <div class="box">
      <img class="image" src="/static/images/next.svg" width="350" height="350">
      <div class="text">
        <block>Next Moves</block>
        <p>We are implementing AI powered systems. By leveraging advanced AI-powered chatbots, businesses can streamline
          their customer support operations and
          enhance overall user satisfaction. The chatbots can handle queries efficiently and effectively, reducing the
          workload on human customer support agents and minimizing response times.</p>

      </div>
    </div>
  </div>
  <div>
    <script>
      function search() {
        var searchTerm = document.getElementById('searchField').value;

        requestData(searchTerm);
        var searchResults = document.getElementById('searchField').value;


      }
      function requestData(searchTerm) {
        const url = '/get/help';

        const data = {
          fetchData: searchTerm
        };

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
          })
          .then(data => {

            console.log(data);
            displaySearchResults(data);
          })
          .catch(error => {

            console.error('There was a problem with the fetch operation:', error);
          });
      }

      function displaySearchResults(results) {

        var resultsDiv = document.getElementById('searchResultsGET');
        resultsDiv.innerHTML = '';
        results.forEach(result => {
          var para = document.createElement('p');
          para.textContent = result; // Usa il contenuto del risultato qui
          resultsDiv.appendChild(para);
        });
      }

    </script>
    <?php
    $a = $AppData->getLatestLogin();

    printf("Latest login: %s - <b>%s</b>", $a["LOGINTIME"], $a["EMAIL"]);
    ?>

  </div>
</div>


 


<script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function () {
    $('#fetchData').on('click', function () {
      var searchTerm = $('#searchField').val();
      console.log(searchTerm);
      $.ajax({
        url: 'http://localhost/get/help',
        type: 'POST',
        data: { fetchData: searchTerm },
        dataType: 'json',
        success: function (data) {

          data.forEach(function (row) {
            console.log(row);
          });
        },
        error: function (xhr, status, error) {
          console.log('Errore nella richiesta: ' + error);
        }
      });
    });

  });


</script>

<?php
include_once("footer.php");
?>

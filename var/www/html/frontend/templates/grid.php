<?php
include_once("header.php");

?>
<script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>
<style>
    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 80%;
        transform: translate(-50%, -50%);
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
        margin: auto;
        border: 1px solid #ccc;
    }

    .work-data {
        border-collapse: collapse;
        border: 1px solid black;
        margin: 0 auto;
        width: 80%;
    }

    .push-data {
        border-collapse: collapse;
        border: 1px solid black;
        margin: 0 auto;
        width: 40%;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        color: #333;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .modal {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: none;
    }

    .modal-content {
        text-align: center;
    }

    .close-button {
        float: right;
        cursor: pointer;
    }

    p {
        font-family: 'Arial', sans-serif;
        font-size: 16px;
        line-height: 1.6;
        padding-left: 20px;
        color: #333333;
        margin-bottom: 15px;
        text-align: justify;
    }

    #customModal {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid #000;
        background-color: white;
        padding: 20px;
        z-index: 1000;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }


    input[type="text"],
    input[type="date"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="checkbox"] {
        margin-right: 5px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    #tableGrid {
        border-collapse: collapse;
        border: 1px solid black;
        margin: 0 auto;
        width: 40%;

    }

    button[type="submit"]:hover {
        background-color: #3e8e41;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    #form,
    #form input,
    #form textarea {
        display: inline-block;
        vertical-align: middle;
        margin-right: 10px;
    }


    .toggle-buttons {
        display: flex;
        flex-wrap: wrap;
    }

    .toggle-button {
        padding: 10px 20px;
        margin: 5px;
        border: 1px solid #ccc;
        background-color: #f2f2f2;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .toggle-button:hover {
        background-color: #e0e0e0;
    }

    #work-data {
        width: 100%;
    }

    .form-group {
        margin-bottom: 15px;
    }


    #form {
        white-space: nowrap;
        overflow-x: auto;
    }

    .help {
        display: none;
    }

    .visible {
        display: block;
    }
</style>
<button onclick="toggleHelp()">Need Help?</button>
<p class="help">In order to load your data inside the system, first you need to be logged in. then double click on
    "TIMESTAMPS"<br> Insert your data... enjoy!<br>
    By clicking on STATS your montly report will be created and the link will by shown in popup</p>

<div class="toggle-buttons">
    <input type="hidden" id="monthref" value="01">

    <input type="hidden" id="usrRef" value="<?php if (isset($_SESSION["id"]))
        echo $_SESSION["id"];
    else
        echo -1; ?>">
    <button class="toggle-button" data-month="01">January</button>
    <button class="toggle-button" data-month="02">February</button>
    <button class="toggle-button" data-month="03">March</button>
    <button class="toggle-button" data-month="04">April</button>
    <button class="toggle-button" data-month="05">May</button>
    <button class="toggle-button" data-month="06">June</button>
    <button class="toggle-button" data-month="07">July</button>
    <button class="toggle-button" data-month="08">August</button>
    <button class="toggle-button" data-month="09">September</button>
    <button class="toggle-button" data-month="10">October</button>
    <button class="toggle-button" data-month="11">November</button>
    <button class="toggle-button" data-month="12">December</button>
    <select name="year-select" id="year-select">

        <?php
        $year = date("Y");
        $old = $year - 1;
        $future = $year + 1;
        echo <<<EOD
      <option value= $old>$old</option>
      <option value= $year selected>$year</option>
      <option value= $future>$future</option>
      
      EOD;
        ?>
    </select>
</div>
<div id="customModal" style="display:none;">
    <div class="modal-content">
        <h4>Dettagli</h4>
        <p id="modalText"></p>
    </div>
    <div class="modal-footer">
        <button onclick="closeModal()">Close</button>
    </div>
</div>
<div id="popup" class="popup">
    <form method="POST" id="formTimestamps" action="">
        <input type=hidden id="userID" name="ID" value=<?php echo $_SESSION['id'] ?>>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" id="date" name="DATA" required>
        </div>
        <div class="form-group">
            <label for="in">In:</label>
            <input type="text" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="hh:mm" required name="IN1" size="5"
                id="in">
        </div>
        <div class="form-group">
            <label for="out">Out:</label>
            <input type="text" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="hh:mm" id="out" name="OUT1"
                size="5" required>
        </div>
        <div class="form-group">
            <label for="in_out">In/Out:</label>
            <input type="text" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="hh:mm" id="in_out" name="IN2"
                size="5">
        </div>
        <div class="form-group">
            <label for="in_out">In/Out:</label>
            <input type="text" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" placeholder="hh:mm" id="in_out" name="OUT2"
                size="5">

        </div>

        <div class="form-group"><label for="holyday">Holyday</label><input type="checkbox" name="HOLYDAY" value="1">

        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" id="description" name="NOTES">
        </div>
        <div class="form-group">
            <input type="submit" value="salva">
        </div>

    </form>

</div>

<div id="uploadModal" class="modal" style="display:none;">
    <div class="modal-content">
       
        <h4>Upload Justification</h4>
        <form id="uploadForm">
            <?php if (isset($_SESSION["full-name"]))
                $fname = $_SESSION["full-name"];
                else $fname='./';
                ?>
            <input type="hidden" value="<?php echo $_SESSION["username"];?>" name="uname_h">
            <input type="file" name="FNAME" required>
            <input type="text" name="dname" placeholder="your filename">
            <input type="submit" value="Load">
            
        </form>
        <p id="uploadStatus"></p>
    </div>
</div>


<div id="tableGrid">
    <table id="work-data">
        <thead>
            <tr>

                <th></th>
                <th></th>
                <th>Date</th>
                <th>In</th>
                <th>Out</th>
                <th>In</th>
                <th>Out</th>

                <th>Description</th>
                <th>
                    <?php if (isset($_SESSION["id"])) {
                        echo '<button type="button" onclick="togglePopup()">+ TIMESTAMP</button>';
                    } ?>
                </th>
                <th><button type="button" onclick="getStats()">STATS</button></th>
                <th><button type="button" onclick="openUploadModal()">UPLOAD</button></th>
            </tr>
        </thead>

    </table>
</div>
<?php
include_once("footer.php") ?>
<script>
    var myname = <?php echo json_encode($_SESSION['username']);?>;
    function getStatistics() {
        console.log(document.getElementById("monthref").value)
    }
</script>
<script src="https://code.berylia.org/jquery/v3.7.1/js/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {

        $("#uploadForm").submit(function (event) {
            event.preventDefault();

            var fileInput = $(this).find("input[type='file']")[0];
            var file = fileInput.files[0];
            var formData = new FormData(this);
            formData.set("file", file);

            $.ajax({
                url: "/v1/timestamps/upload",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#uploadStatus").text("File caricato con successo!");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#uploadStatus").text("Errore durante il caricamento del file: " + errorThrown);
                }
            });
        });


        function closeUploadModal() {
            $("#uploadModal").hide();
        }

        var currentDate = new Date();
        var currentMonth = formatNumber(currentDate.getMonth() + 1);

        document.getElementById("monthref").value = currentMonth;
        var ref = document.getElementById("usrRef").value;
        askData(currentMonth, ref);

        function updateTable(data) {
            var table = document.getElementById("work-data");
            while (table.rows.length > 1) {
                table.deleteRow(1);
            }

            data.forEach(function (rowData) {
                var flag = 0;
                if (rowData) {
                    var row = table.insertRow();
                    rowData.forEach(function (cellData) {
                        console.log(rowData[0])
                        for (var key in cellData) {
                            var cell = row.insertCell();
                            if (key === 'DATA') {
                                var dayOfWeek = new Date(cellData[key]).getDay();
                                console.log(dayOfWeek);

                                if (dayOfWeek === 0 || dayOfWeek === 6) { 
                                    flag = 1;
                                }
                            }
                            if (key === 'HOLYDAY') {
                                if (cellData[key] == '1') flag = 2;
                            }

                            if (flag != 0) {
                                cell.style.backgroundColor = "lightyellow"; 
                            } $(document).ready(function () {
                                $("#uploadForm").submit(function (event) {
                                    event.preventDefault();

                                    var fileInput = $(this).find("input[type='file']")[0];
                                    var file = fileInput.files[0];
                                    var formData = new FormData(this);
                                    formData.set("file", file);

                                    $.ajax({
                                        url: "/v1/timestamps/upload",
                                        type: "POST",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function (data) {
                                            $("#uploadStatus").text("File caricato con successo!");
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            $("#uploadStatus").text("Errore durante il caricamento del file: " + errorThrown);
                                        }
                                    });
                                });
                            });
                            $("#openModalButton").click(function () {
                                if (popup.style.display === "none") {
                                    popup.style.display = "block"; 
                                } else {
                                    popup.style.display = "none"; 
                                }
                            });
                            function closeUploadModal() {
                                $("#uploadModal").hide();
                            }

                            if (key != "HOLYDAY")
                                cell.appendChild(document.createTextNode(cellData[key]));
                        }
                    });
                } else {
                    var row = table.insertRow();
                    var cell = row.insertCell();
                    cell.colSpan = 7;
                }
            });

        }



        function formatNumber(num) {
            return num < 10 ? '0' + num : num.toString();
        }

        function askData(month, ref) {
            month = `${month}`;
            var year = $("#year-select").val();
            console.log(year);

            $.ajax({
                url: '/v1/timestamps/get',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    month: month,
                    year: year,
                    ref: ref
                }),
                success: function (response) {
                    console.log("Richiesta inviata con successo");
                    
                    updateTable(response);
                    console.log("##" + month);
                    $("#monthref").val(month);
                },
                error: function (error) {
                    console.error("Si è verificato un errore durante l'invio della richiesta");
                    console.error(error)
                   
                }
            });
        }

        $(".toggle-button").click(function () {
            var month = $(this).data("month");






            

            askData(month, ref);
        });

        $("#formTimestamps").submit(function (event) {
            event.preventDefault();
            var formData = $(this).serializeArray();
            var data = {};
            $.each(formData, function (index, element) {
                data[element.name] = element.value;
            });
            $.ajax({
                url: '/v1/timestamps/add',
                type: 'POST',
                contentType: 'application/json',

                data: JSON.stringify(data),
                success: function (response) {
                    console.log(response);
                    $("#formTimestamps")[0].reset();
                    togglePopup();
                    location.reload();
                },
                error: function (error) {
                    console.error(error);
                    togglePopup
                }
            });
        });
        $("#form").submit(function (event) {
            event.preventDefault();

            var fileInput = $("input[name='FNAME']")[0];
            var file = fileInput.files[0];
            var formData = new FormData(this);
            formData.set("file", file);

            $.ajax({
                url: "/v1/timestamps/upload",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#modalText").text("File caricato con successo!");
                    $("#customModal").show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#modalText").text("Errore durante il caricamento del file: " + errorThrown);
                    $("#customModal").show();
                }
            });
        });

        function closeModal() {
            $("#customModal").hide();
        }

       
    });

    function getStats() {
        
        var month = document.getElementById("monthref").value;
        if (month == '') month = 1;
        var userID = parseInt(document.getElementById("userID").value, 10);
        var year = parseInt(document.getElementById("year-select").value, 10);

        $.ajax({
            url: '/v1/timestamps/stats',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                month: month,
                year: year,
                uid: userID
            }),
            success: function (response) {
              
                HOLA(response);
                console.log(response);

            },
            error: function (error) {
                console.error("Si è verificato un errore durante l'invio della richiesta");
            }
        });
    }

    function HOLA(data) {
        var popupContent = "TotalHours: " + data.TotalHours + "<br>" +
            "ExtraHours: " + data.ExtraHours + "<br>" +
            "WorkedDays: " + data.WorkedDays + "<br>" +
            "<a href='/users/"+myname+"/report.pdf'>Report</a>";
            

        document.getElementById('modalText').innerHTML = popupContent;
        document.getElementById('customModal').style.display = 'block';


    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    function closeModal2() {
        document.getElementById('customModal2').style.display = 'none';
    }

    function openUploadModal(){
        const popup = document.getElementById("uploadModal");
        if (popup.style.display === "none") {
            popup.style.display = "block";
        } else {
            popup.style.display = "none"; 
        }
    }
    function togglePopup() {
        const popup = document.getElementById("popup");
        if (popup.style.display === "none") {
            popup.style.display = "block"; 
        } else {
            popup.style.display = "none";
        }
    }
    function toggleHelp() {
        var helpElements = document.getElementsByClassName('help');
        for (var i = 0; i < helpElements.length; i++) {
            helpElements[i].classList.toggle('visible');
        }
    }

</script>

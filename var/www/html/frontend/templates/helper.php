<?php
include_once('header.php');

use App\Classes\LogRecord;
use App\Models\DB;

// Log the request
if (isset($_SESSION["full-name"])) {
    $logs->write(new LogRecord(
        date("Y-m-d H:i:s"),
        "info", 
        htmlspecialchars($_SESSION["full-name"], ENT_QUOTES, 'UTF-8') . " requests: " . 
        htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8')
    );
} else {
    $logs->write(new LogRecord(
        date("Y-m-d H:i:s"),
        "info", 
        "someone requests: " . htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8')
    ));
}

$pdo = new DB;
$c = $pdo->connect();

if(isset($_POST["submit"])) {
    try {
        // Validate and sanitize input
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $help = filter_input(INPUT_POST, 'help', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $keywords = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        
        // Additional validation
        if (empty($title) || empty($help)) {
            throw new Exception("Title and help content are required");
        }
        
        // Use prepared statements
        $statement = $c->prepare("INSERT INTO HELPINFO (TITLE, HELP, KEYWORDS) VALUES (:title, :help, :keywords)");
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':help', $help, PDO::PARAM_STR);
        $statement->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        
        if ($statement->execute()) {
            echo '<div class="alert alert-success">Record added successfully</div>';
        } else {
            throw new Exception("Failed to insert record");
        }
        
    } catch (Exception $e) {
        // Log the error but don't show details to user
        error_log("Database error: " . $e->getMessage());
        echo '<div class="alert alert-danger">An error occurred. Please try again.</div>';
    }
}
?>

<style>
    .form-container {
        max-width: 65%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    button:hover {
        background-color: #45a049;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
</style>

<div class="form-container">
    <form id="myForm" action="" method="POST">
        <div class="form-group">
            <label for="title">Titolo:</label>
            <input type="text" id="title" name="title" required maxlength="255" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : '' ?>">
        </div>
        <div class="form-group">
            <label for="keywords">Keywords:</label>
            <input type="text" id="keywords" name="keywords" maxlength="255" value="<?= isset($_POST['keywords']) ? htmlspecialchars($_POST['keywords'], ENT_QUOTES, 'UTF-8') : '' ?>">
        </div>
        <div class="form-group">
            <label for="help">HELP:</label>
            <textarea id="help" name="help" required><?= isset($_POST['help']) ? htmlspecialchars($_POST['help'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        </div>
        <button type="submit" name="submit">Invia</button>
    </form>
</div>

<?php
include_once('footer.php');
?>
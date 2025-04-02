<?php
include_once("header.php");
if (isset($_SESSION['id'])){
$url='http://localhost/list-directory/'.$_SESSION['id'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);
curl_close($ch);

$files = json_decode($response, true);
}else{
    $files="";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your files</title>
</head>
<body>
    <h1>Files/Directory</h1>

    <?php if ($files && count($files) > 0): ?>
        <ul>
        <?php foreach ($files as $file): 
                $fname = base64_encode("users/".$_SESSION["username"]."/".$file) ?>
                <li><?php echo "<a href='/v1/users/files/show?fname=$fname'>".$file."</a>"; ?></li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No files found.</p>
    <?php endif; ?>
</body>
<?php
include_once("footer.php");
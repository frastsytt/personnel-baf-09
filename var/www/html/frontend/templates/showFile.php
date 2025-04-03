<?php
include_once("header.php");

// Define allowed file extensions and their corresponding MIME types
$authorized = [
    "txt" => "text/plain",
    "md" => "text/markdown",
    "MD" => "text/markdown",
    "png" => "image/png",
    "jpg" => "image/jpeg",
    "jpeg" => "image/jpeg"
];

// Define safe base directory for files
$baseDir = realpath("../public/") . DIRECTORY_SEPARATOR;

if (isset($_COOKIE["filename"])) {
    // Sanitize the filename
    $filename = basename($_COOKIE["filename"]);
    $filePath = $baseDir . $filename;
    
    // Verify the file exists and is within the allowed directory
    if (file_exists($filePath) && strpos(realpath($filePath), $baseDir) === 0) {
        $infoPercorso = pathinfo($filePath);
        
        if (isset($infoPercorso['extension'])) {
            $estensione = strtolower($infoPercorso['extension']);
            
            if (array_key_exists($estensione, $authorized)) {
                // For text files, read and output with HTML escaping
                if (in_array($authorized[$estensione], ["text/plain", "text/markdown"])) {
                    echo "<div>";
                    echo htmlspecialchars(file_get_contents($filePath), ENT_QUOTES, 'UTF-8');
                    echo "</div>";
                } 
                // For images, use proper HTML tags
                elseif (strpos($authorized[$estensione], "image/") === 0) {
                    echo '<div><img src="'.htmlspecialchars("/public/".$filename, ENT_QUOTES, 'UTF-8').'" alt="User uploaded image"></div>';
                }
            } else {
                // For unauthorized extensions, don't open directly - provide download link
                echo '<p>File type not allowed for direct viewing. <a href="'
                    .htmlspecialchars("/public/".$filename, ENT_QUOTES, 'UTF-8')
                    .'" download>Download file</a></p>';
            }
        } else {
            // No extension - treat as potentially dangerous
            echo '<p>Invalid file type</p>';
        }
    } else {
        // File doesn't exist or is outside allowed directory
        echo '<p>File not found</p>';
    }
}

include_once("footer.php");
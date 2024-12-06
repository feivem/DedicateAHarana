<?php 
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "root";
    $db = "Harayahin";

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
?>

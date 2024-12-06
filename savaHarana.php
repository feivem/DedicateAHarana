<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = $_POST['to'];
    $from = $_POST['from'];
    $message = $_POST['message'];

    // Handle file upload
    $file = $_FILES['haranaFile'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['mp3', 'wav'];

    if (in_array($fileExt, $allowed) && $fileError === 0 && $fileSize < 10000000) {
        $fileNewName = uniqid('', true) . "." . $fileExt;
        $fileDestination = 'uploads/' . $fileNewName;
        move_uploaded_file($fileTmpName, $fileDestination);
    } else {
        echo "File upload error.";
    }

    // Insert data into database
    $query = "INSERT INTO harana_dedications (to_user, from_user, message, file_name) 
              VALUES ('$to', '$from', '$message', '$fileNewName')";

    if (mysqli_query($conn, $query)) {
        echo "Harana dedicated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

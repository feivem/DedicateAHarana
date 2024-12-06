<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
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

        // Update the entry with new file
        $query = "UPDATE harana_dedications 
                  SET to_user='$to', from_user='$from', message='$message', file_name='$fileNewName' 
                  WHERE id='$id'";

        if (mysqli_query($conn, $query)) {
            echo "Harana updated successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

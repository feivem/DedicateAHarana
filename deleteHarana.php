<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Delete the record from the database
    $query = "DELETE FROM harana_dedications WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "Harana deleted successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

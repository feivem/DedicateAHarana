<?php
// Include the database connection
require_once('db_connection.php');

// Fetch previous entries
$query = "SELECT * FROM harana_dedications ORDER BY timestamp DESC";
$result = $conn->query($query);

$entries = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $entries[] = $row;
    }
}
?>
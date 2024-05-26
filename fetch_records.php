<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "csdexpre_csddb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get dates from POST
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Query to retrieve records between start and end dates
$query = "SELECT * FROM entry WHERE bdt BETWEEN '$start_date' AND '$end_date'";
$result = $conn->query($query);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode($data);
?>

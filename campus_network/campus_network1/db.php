<?php
$servername = "localhost";
$username = "if0_39163957"; // Replace with your database username
$password = "Lathasrikaku99"; // Replace with your database password
$dbname = "campus_network1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

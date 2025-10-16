<?php
$servername = "localhost"; // Usually 'localhost'
$username = "root";        // Default username for XAMPP
$password = "";            // Default password for XAMPP is empty
$dbname = "campus_network1"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

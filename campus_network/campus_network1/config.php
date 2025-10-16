<?php
// config.php - Database connection settings

$servername = "localhost"; // Typically 'localhost'
$username = "root";        // Default username for MySQL
$password = "";            // Default password for MySQL (blank for XAMPP)
$dbname = "campus_network1";  // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

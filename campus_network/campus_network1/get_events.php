<?php
// Database connection
$host = 'localhost'; // Database host
$db = 'campus_network1'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

// Create a PDO instance to interact with MySQL
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch all events from the database
$stmt = $pdo->query("SELECT * FROM placement_events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format the events in a way that can be used in the calendar
$formattedEvents = [];
foreach ($events as $event) {
    $formattedEvents[$event['event_date']] = [
        'company' => $event['company_name'],
        'rounds' => $event['rounds'],
        'roundTypes' => $event['round_types']
    ];
}

// Return the events as JSON
echo json_encode($formattedEvents);
?>

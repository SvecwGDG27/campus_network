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

// Handle form submission for adding or updating an event
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['date'];
    $company = $_POST['company'];
    $rounds = $_POST['rounds'];
    $roundTypes = $_POST['roundTypes'];

    // Check if event exists
    $stmt = $pdo->prepare("SELECT * FROM placement_events WHERE event_date = :date AND company_name = :company");
    $stmt->execute(['date' => $date, 'company' => $company]);
    $event = $stmt->fetch();

    if ($event) {
        // Update existing event
        $stmt = $pdo->prepare("UPDATE placement_events SET rounds = :rounds, round_types = :roundTypes WHERE event_date = :date AND company_name = :company");
        $stmt->execute(['rounds' => $rounds, 'roundTypes' => $roundTypes, 'date' => $date, 'company' => $company]);
        $message = "Event updated successfully!";
    } else {
        // Insert new event
        $stmt = $pdo->prepare("INSERT INTO placement_events (event_date, company_name, rounds, round_types) VALUES (:date, :company, :rounds, :roundTypes)");
        $stmt->execute(['date' => $date, 'company' => $company, 'rounds' => $rounds, 'roundTypes' => $roundTypes]);
        $message = "Event added successfully!";
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $dateToDelete = $_GET['delete'];

    // Delete event from the database
    $stmt = $pdo->prepare("DELETE FROM placement_events WHERE event_date = :date");
    $stmt->execute(['date' => $dateToDelete]);
    $message = "Event deleted successfully!";
}

// Fetch all events for displaying
$stmt = $pdo->query("SELECT * FROM placement_events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Modify Placement Calendar</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #e7feff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #016064;
            margin-top: 50px;
        }

        /* Message Box */
        .message {
            text-align: center;
            font-weight: bold;
            color: green;
            margin-top: 20px;
        }

        /* Form Styling */
        form {
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }

        form input,
        form textarea,
        form button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        form input[type="date"],
        form input[type="number"] {
            font-size: 1rem;
        }

        form textarea {
            font-family: Arial, sans-serif;
        }

        form button {
            background-color: #016064;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
        }

        form button:hover {
            background-color: #016064;
        }

        /* Event List Styling */
        .event-list {
            width: 80%;
            margin: 40px auto;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }

        .event-list h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
        }

        table td {
            font-size: 1rem;
        }

        table td a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        table td a:hover {
            text-decoration: underline;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        /* Mobile Responsiveness */
        @media screen and (max-width: 768px) {
            form {
                width: 90%;
            }

            .event-list {
                width: 90%;
            }

            table {
                font-size: 0.9rem;
            }

            table th, table td {
                padding: 8px;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<h1>Admin - Modify Placement Calendar</h1>

<?php if (isset($message)) : ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>

<!-- Form for adding/updating events -->
<form method="POST" action="">
    <h2>Add/Update Event</h2>
    <label for="date">Date (YYYY-MM-DD):</label>
    <input type="date" id="date" name="date" required>

    <label for="company">Company Name:</label>
    <input type="text" id="company" name="company" required>

    <label for="rounds">Number of Rounds:</label>
    <input type="number" id="rounds" name="rounds" required>

    <label for="roundTypes">Round Types (comma-separated):</label>
    <textarea id="roundTypes" name="roundTypes" rows="3" required></textarea>

    <button type="submit">Save Event</button>
</form>

<!-- List of events -->
<div class="event-list">
    <h2>Existing Events</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Company</th>
                <th>Rounds</th>
                <th>Round Types</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($events)) : ?>
                <?php foreach ($events as $event) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($event['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['rounds']); ?></td>
                        <td><?php echo htmlspecialchars($event['round_types']); ?></td>
                        <td>
                            <a href="?delete=<?php echo urlencode($event['event_date']); ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

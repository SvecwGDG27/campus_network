<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "<p>You need to <a href='login.php'>login</a> to view your profile.</p>";
    exit();
}

// Fetch user email from session
$user_email = $_SESSION['user_email'];

// Fetch user details from the database
$query = $conn->prepare("SELECT name FROM users WHERE email = ?");
$query->bind_param("s", $user_email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['name'];
} else {
    echo "<p>User not found. Please contact support.</p>";
    exit();
}

// Fetch user scores from the quiz_scores table
$scores_query = $conn->prepare("SELECT score, date FROM quiz_scores WHERE user_email = ?");
$scores_query->bind_param("s", $user_email);
$scores_query->execute();
$scores_result = $scores_query->get_result();

// Prepare data for the graph
$scores = [];
$dates = [];

while ($row = $scores_result->fetch_assoc()) {
    $scores[] = $row['score'];
    $dates[] = $row['date'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #e7feff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .header nav ul li {
            display: inline;
        }

        .header nav ul li a {
            text-decoration: none;
            color: black;
            font-size: 16px;
            padding: 8px 16px;
            border: 1px solid transparent;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .header nav ul li a:hover {
            color: white;
            background-color: #016064;
            border-color: #016064;
        }

        .header .logo {
            height: 50px;
            cursor: pointer;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            transition: color 0.3s;
        }

        .header .logo:hover {
            color: #016064;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            margin: 50px auto;
            width: 80%;
            height: 400px; /* Set the height for both containers */
        }

        .profile-container {
            width: 45%;
            height: 50%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        .profile-container h2 {
            margin-bottom: 10px; /* Reduced margin between Welcome text and Email */
            color: #333;
        }

        .profile-container p {
            margin: 5px 0; /* Reduced margin between Email and Email ID */
            font-size: 16px;
            color: #555;
        }

        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #c9302c;
        }

        .chart-container {
            width: 45%;
            height: 100%; /* Ensure it takes up the full height of the container */
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        canvas {
            width: 100%;
            height: 100%; /* Make the chart fill the container */
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo">Campus Network</div>
    <nav>
        <ul>
            <li><a href="placement-calendar.php">Placement Calendar</a></li>
            <li><a href="interview-questions.php">Interview Questions</a></li>
            <li><a href="mock-interview.php">Daily practice</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="main-container">
        <!-- Profile Box -->
        <div class="profile-container">
            <h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
            <strong>Email:</strong><br>
            <?= htmlspecialchars($user_email) ?>
            <a href="index.html" class="logout-button">Logout</a>
        </div>

        <!-- Chart Box -->
        <div class="chart-container">
            <h3>Your Quiz Scores Over Time</h3>
            <canvas id="scoreChart"></canvas>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2024 Campus Network. All rights reserved.</p>
</footer>

<script>
    // Prepare data for the graph
    const labels = <?= json_encode($dates) ?>;
    const data = {
        labels: labels,
        datasets: [{
            label: 'Quiz Scores',
            data: <?= json_encode($scores) ?>,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
            borderWidth: 3 // Thicker line (default is 1)
        }]
    };

    const config = {
        type: 'line', // Line chart
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Quiz Scores Over Time'
                }
            }
        }
    };

    // Render the chart
    const scoreChart = new Chart(
        document.getElementById('scoreChart'),
        config
    );
</script>
</body>
</html>

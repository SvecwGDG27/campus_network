<?php
// Start the session securely
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: adminlogin.php");
    exit();
}

// Regenerate session ID to ensure security
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Data</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Main Content */
        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            font-size: 1.1em;
            color: #555;
            margin-bottom: 40px;
        }

        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }

        .admin-actions form {
            display: inline-block;
        }

        .admin-actions button {
            background-color: #016064;
            color: white;
            font-size: 1.1em;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 200px;
        }

        .admin-actions button:hover {
            background-color: #191970;
        }

        .admin-actions button:focus {
            outline: none;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 50px;
        }

        footer p {
            margin: 0;
            font-size: 1em;
        }
    </style>
</head>
<body>
<main>
    <h1>Admin Dashboard</h1>
    <p>Welcome, Admin! You can manage the Placement Calendar, Interview Questions, and Mock Interview here.</p>

    <div class="admin-actions">
        <form action="placement-calendar-admin.php" method="GET">
            <button type="submit">Modify Placement Calendar</button>
        </form>
        <form action="interview-questions-modify.php" method="GET">
            <button type="submit">Modify Interview Questions</button>
        </form>
        <form action="mock-interview-modify.php" method="GET">
            <button type="submit">Modify Daily Practise</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 Campus Network. All rights reserved.</p>
</footer>

</body>
</html>

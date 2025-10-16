<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "Please log in to submit feedback.";
    exit();
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    $userEmail = $_SESSION['user_email']; // Get the logged-in user's email

    // Prepare and execute the SQL query to insert feedback
    $query = $conn->prepare("INSERT INTO feedback (rating, message, user_email) VALUES (?, ?, ?)");
    $query->bind_param("iss", $rating, $message, $userEmail);

    if ($query->execute()) {
        $feedbackSubmitted = true;
    } else {
        echo "Error submitting feedback: " . $query->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .feedback-form {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feedback-form h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .feedback-form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        .feedback-form input[type="radio"] {
            margin-right: 10px;
        }

        .feedback-form .rating {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .feedback-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        .feedback-form button {
            width: 100%;
            padding: 12px;
            background-color: #016064;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .feedback-form button:hover {
            background-color: #5072a7;
        }

        .thank-you-message {
            text-align: center;
            font-size: 24px;
            color: green;
            display: none;
            margin-top: 20px;
        }

        .thank-you-message a {
            display: inline-block;
            margin-top: 20px;
            font-size: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .thank-you-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="feedback-form" method="POST" action="feedback.php">
            <h2>We Value Your Feedback</h2>

            <?php if (isset($feedbackSubmitted) && $feedbackSubmitted): ?>
                <div class="thank-you-message">
                    Thank you for your feedback!<br>
                    <a href="index.html">Go back to the home page</a>
                </div>
            <?php else: ?>
                <label for="rating">Rating</label>
                <div class="rating">
                    <label><input type="radio" name="rating" value="1" required> 1</label>
                    <label><input type="radio" name="rating" value="2" required> 2</label>
                    <label><input type="radio" name="rating" value="3" required> 3</label>
                    <label><input type="radio" name="rating" value="4" required> 4</label>
                    <label><input type="radio" name="rating" value="5" required> 5</label>
                </div>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4" placeholder="Write your feedback here..." required></textarea>

                <button type="submit">Submit Feedback</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>

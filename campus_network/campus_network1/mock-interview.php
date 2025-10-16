<?php
// Database connection
include('db_connection.php');

// Start the session to access user data like email
session_start();

// Handle form submission (quiz submission)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $score = 0;

    // Fetch questions
    $query = "SELECT * FROM mock_questions";
    $result = $conn->query($query);

    if ($result === false) {
        die("Error fetching questions: " . $conn->error);
    }

    while ($question = $result->fetch_assoc()) {
        $questionId = $question['id'];
        $correctAnswer = $question['answer'];
        $userAnswer = $_POST["question_$questionId"] ?? '';

        if ($userAnswer === $correctAnswer) {
            $score++;
        }
    }

    // Ensure user email is available in the session
    if (!isset($_SESSION['user_email'])) {
        echo "Error: User is not logged in.";
        exit();
    }

    $userEmail = $_SESSION['user_email'];
    $date = date('Y-m-d');
    $insertQuery = "INSERT INTO quiz_scores (user_email, score, date) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($insertQuery)) {
        $stmt->bind_param("sis", $userEmail, $score, $date);
        $stmt->execute();
        
        $message = $stmt->affected_rows > 0 ? "Score saved successfully!" : "Failed to save score.";
        $stmt->close();
    } else {
        echo "Error: Unable to prepare query.";
        exit();
    }

    // Score messages based on marks
    if ($score < 5) {
        $performanceMessage = "Better luck next time!";
        $messageColor = "red";
    } elseif ($score <= 9) {
        $performanceMessage = "Good job! Keep practicing.";
        $messageColor = "orange";
    } else { // $score == 10
        $performanceMessage = "Congratulations! You are a professional!";
        $messageColor = "green";
    }

    // Display the score and performance message
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Quiz Results</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e7feff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: $messageColor;
        }

        p {
            font-size: 1.2rem;
            font-weight: bold;
            color: $messageColor;
        }

        .result-container {
            width: 90%;
            max-width: 600px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: slideIn 1s ease-out;
        }

        .retry-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #016064;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .retry-btn:hover {
            background-color: #00416a;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class='result-container'>
        <h2>Your Score: $score / {$result->num_rows}</h2>
        <p>$performanceMessage</p>
        <a href='mock-interview.php' class='retry-btn'>Try Again</a>
    </div>
</body>
</html>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Platform</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e7feff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            text-align: center;
            color: #016064;
            margin-bottom: 10px;
        }

        .quiz-container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            animation: slideIn 1s ease-out;
        }

        .question {
            margin-bottom: 25px;
        }

        .question h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .options-container label {
            display: block;
            background: #f7f9fc;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #ddd;
        }

        .options-container input[type="radio"] {
            margin-right: 10px;
        }

        .options-container label:hover {
            background-color: #e9eff8;
            border-color: #016064;
        }

        .submit-btn {
            display: inline-block;
            width: 100%;
            background-color: #016064;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #00416a;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Welcome to the Quiz!</h1>
    <div class="quiz-container">
        <form method="POST" action="mock-interview.php">
            <?php
            $query = "SELECT * FROM mock_questions";
            $result = $conn->query($query);
            while ($question = $result->fetch_assoc()):
            ?>
                <div class="question">
                    <h3><?php echo htmlspecialchars($question['question']); ?></h3>
                    <div class="options-container">
                        <?php foreach (['option1', 'option2', 'option3', 'option4'] as $option): ?>
                            <label>
                                <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo htmlspecialchars($question[$option]); ?>" required>
                                <?php echo htmlspecialchars($question[$option]); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            <button type="submit" class="submit-btn">Submit Quiz</button>
        </form>
    </div>
</body>
</html>

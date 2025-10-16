<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    die("<p>You must be logged in to use this feature. <a href='login.php'>Login here</a></p>");
}

// Handle question submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $question = $conn->real_escape_string($_POST['question']);
    $email = $_SESSION['user_email'];

    // Insert question into the database
    $stmt = $conn->prepare("INSERT INTO faq (question, user_email) VALUES (?, ?)");
    $stmt->bind_param("ss", $question, $email);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Question posted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Retrieve all questions
$faq_query = "SELECT * FROM faq ORDER BY id DESC";
$faq_result = $conn->query($faq_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e7feff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #016064;
            color: white;
        }
        .question-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .question-form button {
            padding: 10px 20px;
            background-color:#007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .faq-list .faq-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .answer-form textarea {
            width: calc(100% - 20px);
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .answer-form button {
            margin-left: 10px;
            padding: 5px 10px;
            background-color:#007bff ;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .answers-container {
            display: none;
            margin-top: 10px;
        }
        .view-answers-button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .view-answers-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function toggleAnswers(id) {
            const answersContainer = document.getElementById(`answers-${id}`);
            if (answersContainer.style.display === "none") {
                answersContainer.style.display = "block";
            } else {
                answersContainer.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FAQ Section</h1>
        </div>
        <form method="POST" class="question-form">
            <textarea name="question" placeholder="Type your question here..." rows="4" required></textarea>
            <button type="submit">Post Question</button>
        </form>
        <div class="faq-list">
            <?php
            if ($faq_result->num_rows > 0) {
                while ($row = $faq_result->fetch_assoc()) {
                    $faq_id = $row['id'];

                    // Get the count of answers for this question
                    $answer_count_query = "SELECT COUNT(*) AS answer_count FROM faq_answers WHERE faq_id = $faq_id";
                    $answer_count_result = $conn->query($answer_count_query);
                    $answer_count = $answer_count_result->fetch_assoc()['answer_count'];
                    ?>
                    <div class="faq-item">
                    <p><strong>Question:</strong> <?php echo htmlspecialchars($row['question']); ?> 
<strong>(<?php echo $answer_count; ?> Answer<?php echo $answer_count !== 1 ? 's' : ''; ?>)</strong></p>

                        <button class="view-answers-button" onclick="toggleAnswers(<?php echo $faq_id; ?>)">View Answers</button>

                        <div id="answers-<?php echo $faq_id; ?>" class="answers-container">
                            <ul>
                                <?php
                                $answer_query = "SELECT answer FROM faq_answers WHERE faq_id = $faq_id";
                                $answer_result = $conn->query($answer_query);

                                if ($answer_result->num_rows > 0) {
                                    while ($answer_row = $answer_result->fetch_assoc()) {
                                        echo "<li>" . htmlspecialchars($answer_row['answer']) . "</li>";
                                    }
                                } else {
                                    echo "<li>No answers yet.</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Answer form -->
                        <form method="POST" class="answer-form">
                            <textarea name="answer" placeholder="Type your answer here..." rows="2" required></textarea>
                            <button type="submit" name="answer_question" value="<?php echo $faq_id; ?>">Submit Answer</button>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No questions posted yet.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php
// Handle answer submission
if (isset($_POST['answer_question'])) {
    $answer = $conn->real_escape_string($_POST['answer']);
    $faq_id = intval($_POST['answer_question']);
    $user_email = $_SESSION['user_email'];

    // Insert answer into the database
    $stmt = $conn->prepare("INSERT INTO faq_answers (faq_id, answer, user_email) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $faq_id, $answer, $user_email);
    if ($stmt->execute()) {
        header('Location: faq.php'); // Refresh the page to display the new answer
        exit();
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>

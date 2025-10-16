<?php
// Start the session securely
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: adminlogin.php");
    exit();
}

// Include the database connection
include('db_connection.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    for ($i = 1; $i <= 10; $i++) {
        // Get the submitted data for each question
        $question = mysqli_real_escape_string($conn, $_POST["question{$i}"]);
        $option1 = mysqli_real_escape_string($conn, $_POST["option1_{$i}"]);
        $option2 = mysqli_real_escape_string($conn, $_POST["option2_{$i}"]);
        $option3 = mysqli_real_escape_string($conn, $_POST["option3_{$i}"]);
        $option4 = mysqli_real_escape_string($conn, $_POST["option4_{$i}"]);
        $answer = mysqli_real_escape_string($conn, $_POST["answer_{$i}"]);

        // Check if the record already exists
        $query = "SELECT * FROM mock_questions WHERE id = $i";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            // Update existing record
            $update_query = "UPDATE mock_questions SET 
                question = '$question',
                option1 = '$option1',
                option2 = '$option2',
                option3 = '$option3',
                option4 = '$option4',
                answer = '$answer'
                WHERE id = $i";
            $conn->query($update_query);
        } else {
            // Insert new record
            $insert_query = "INSERT INTO mock_questions (id, question, option1, option2, option3, option4, answer)
                VALUES ($i, '$question', '$option1', '$option2', '$option3', '$option4', '$answer')";
            $conn->query($insert_query);
        }
    }
    echo "<script>alert('Questions updated successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Mock Questions</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #e7feff;
            color: #333;
        }
        .container {
            width: 70%;
            margin: auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #016064;
        }
        form {
            margin-top: 20px;
        }
        .question-container {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #016064;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #016064;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>modify daily practise Questions</h1>
    <form method="POST" action="">
        <?php
        for ($i = 1; $i <= 10; $i++) {
            // Fetch the current question data
            $query = "SELECT * FROM mock_questions WHERE id = $i";
            $result = $conn->query($query);
            $question_data = $result->fetch_assoc() ?? [
                'question' => '',
                'option1' => '',
                'option2' => '',
                'option3' => '',
                'option4' => '',
                'answer' => ''
            ];
        ?>
            <div class="question-container">
                <label for="question<?php echo $i; ?>">Question <?php echo $i; ?></label>
                <input type="text" name="question<?php echo $i; ?>" value="<?php echo htmlspecialchars($question_data['question']); ?>" required>
                
                <label for="option1_<?php echo $i; ?>">Option 1</label>
                <input type="text" name="option1_<?php echo $i; ?>" value="<?php echo htmlspecialchars($question_data['option1']); ?>" required>

                <label for="option2_<?php echo $i; ?>">Option 2</label>
                <input type="text" name="option2_<?php echo $i; ?>" value="<?php echo htmlspecialchars($question_data['option2']); ?>" required>

                <label for="option3_<?php echo $i; ?>">Option 3</label>
                <input type="text" name="option3_<?php echo $i; ?>" value="<?php echo htmlspecialchars($question_data['option3']); ?>" required>

                <label for="option4_<?php echo $i; ?>">Option 4</label>
                <input type="text" name="option4_<?php echo $i; ?>" value="<?php echo htmlspecialchars($question_data['option4']); ?>" required>

                <label for="answer_<?php echo $i; ?>">Correct Answer</label>
                <input type="text" name="answer_<?php echo $i; ?>" value="<?php echo htmlspecialchars($question_data['answer']); ?>" required>
            </div>
        <?php } ?>
        <button type="submit">Save Questions</button>
    </form>
</div>

</body>
</html>

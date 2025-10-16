<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];

    $query = "INSERT INTO faq (question) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $question);

    if ($stmt->execute()) {
        echo "Question posted.";
    } else {
        echo "Failed to post question.";
    }
}
?>

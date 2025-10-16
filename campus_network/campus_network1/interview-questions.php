<?php
// Database connection
$host = 'localhost';
$db = 'campus_network1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Handle AJAX requests for company-specific data
if (isset($_GET['company'])) {
    $company = $_GET['company'];

    // Fetch round types and questions for the selected company
    $stmt = $pdo->prepare("SELECT round_types, questions FROM interview_questions WHERE company_name = :company");
    $stmt->execute(['company' => $company]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];

    if ($result) {
        foreach ($result as $row) {
            $round = trim($row['round_types']);
            $question = trim($row['questions']);

            if (!isset($data[$round])) {
                $data[$round] = [];
            }
            $data[$round][] = $question;
        }
    }

    echo json_encode($data);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Questions</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color:  #232f3e;
            color: white;
            padding: 1rem 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        #company-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 20px;
            padding: 0;
        }

        button {
            padding: 10px 20px;
            background-color: #0095b6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #007aa5;
            transform: scale(1.05);
        }

        #questions {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #questions h3 {
            margin-bottom: 10px;
            color: #333;
            border-bottom: 2px solid  #add8e6;
            padding-bottom: 5px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        footer {
            text-align: center;
            padding: 1rem 0;
            background: #232f3e;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
    <script>
        function showQuestions(company) {
            fetch(`interview-questions.php?company=${encodeURIComponent(company)}`)
                .then(response => response.json())
                .then(data => {
                    const questionsDiv = document.getElementById('questions');
                    questionsDiv.innerHTML = ''; // Clear existing questions

                    if (Object.keys(data).length > 0) {
                        for (const round in data) {
                            const roundDiv = document.createElement('div');
                            const roundTitle = document.createElement('h3');
                            roundTitle.textContent = `Round: ${round}`;
                            roundDiv.appendChild(roundTitle);

                            const questionList = document.createElement('ul');
                            data[round].forEach(question => {
                                const questionItem = document.createElement('li');
                                questionItem.textContent = question;
                                questionList.appendChild(questionItem);
                            });
                            roundDiv.appendChild(questionList);

                            questionsDiv.appendChild(roundDiv);
                        }
                    } else {
                        questionsDiv.innerHTML = '<p>No data available for this company.</p>';
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    </script>
</head>
<body>
    <header>
        <h1>Interview Questions by Company</h1>
    </header>

    <!-- Company Buttons -->
    <div id="company-buttons">
        <?php
        $stmt = $pdo->query("SELECT DISTINCT company_name FROM interview_questions");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<button onclick="showQuestions(\'' . htmlspecialchars($row['company_name']) . '\')">' . htmlspecialchars($row['company_name']) . '</button>';
        }
        ?>
    </div>

    <!-- Questions Section -->
    <div id="questions">
        <p>Select a company to view questions categorized by round.</p>
    </div>

    <footer>
        <p>&copy; 2024 Interview Questions Portal. All Rights Reserved.</p>
    </footer>
</body>
</html>

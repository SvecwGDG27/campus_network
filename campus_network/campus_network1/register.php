<?php 
// Include database connection
require 'db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Allowed email domain
    $allowedDomain = 'svecw.edu.in';
    $emailParts = explode('@', $email);

    if (count($emailParts) === 2 && $emailParts[1] === $allowedDomain) {
        // Check if email already exists
        $checkQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $result = $checkQuery->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = "Email already exists!";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $query = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $query->bind_param("sss", $name, $email, $hashedPassword);

            if ($query->execute()) {
                // Redirect to login page
                header("Location: login.php");
                exit();
            } else {
                $errorMessage = "Error: " . $query->error;
            }
        }
    } else {
        $errorMessage = "Invalid email domain! Only '@svecw.edu.in' emails are allowed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        .navbar {
            background: #232f3e; /* Matching header background color */
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 1rem;
        }
        .navbar ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        .navbar ul li a:hover {
            color: #016064; /* Matching hover effect */
        }
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 4rem);
            background-color: #d8e5d1; /* Matching main background color */
        }
        .register-box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .register-box h1 {
            text-align: center;
            margin-bottom: 1rem;
            color: #333;
        }
        .register-box label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }
        .register-box input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .register-box button {
            width: 100%;
            padding: 0.8rem;
            background: #232f3e; /* Matching button background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-box button:hover {
            background: #016064; /* Matching hover effect */
        }
        .error-message {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 1rem;
            background: #232f3e; /* Matching footer background */
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
       
    </style>
</head>
<body>
   
    <main class="main-container">
        <div class="register-box">
            <h1>Register</h1>
            <!-- Display error messages -->
            <?php if (!empty($errorMessage)) { echo "<p class='error-message'>$errorMessage</p>"; } ?>
            <form method="POST" action="register.php">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Register</button>
            </form>
            <p style="text-align:center;">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </main>
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Your Website Name. All rights reserved.</p>
    </div>
</body>
</html>

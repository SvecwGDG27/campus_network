<?php 
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $errorMessage = ""; // Initialize error message

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        // Prepare SQL query
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$query) {
            $errorMessage = "Query preparation failed: " . $conn->error;
        } else {
            $query->bind_param("s", $email);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $hashedPassword = $user['password'];

                // Verify password
                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['user_email'] = $email; // Use a consistent session variable name
                    header("Location: afteruserlogin.php");
                    exit();
                } else {
                    $errorMessage = "Incorrect password.";
                }
            } else {
                $errorMessage = "No user found with this email.";
            }

            $query->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        .navbar {
            background: #232f3e; /* Header style similar to Register page */
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
            background-color: #d8e5d1; /* Consistent main background color */
        }
        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .login-box h1 {
            text-align: center;
            margin-bottom: 1rem;
            color: #333;
        }
        .login-box label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }
        .login-box input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-box button {
            width: 100%;
            padding: 0.8rem;
            background: #232f3e; /* Matching button background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-box button:hover {
            background: #016064; /* Matching hover effect */
        }
        .login-box .error-message {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 1rem;
            background: #232f3e; /* Consistent footer style */
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
   
    <main class="main-container">
        <div class="login-box">
            <h1>Login</h1>
            <!-- Display error message -->
            <?php if (!empty($errorMessage)): ?>
                <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <p style="text-align: center;">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </main>

    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Your Website Name. All rights reserved.</p>
    </div>
</body>
</html>

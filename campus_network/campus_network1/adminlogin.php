<?php
// Start session
session_start();

// Define hardcoded admin credentials
$admin_email = "lathasribtech@gmail.com";
// Use a hashed password for security
$admin_password_hashed = password_hash('9989077682', PASSWORD_BCRYPT); // Hash of '9989077682'

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Sanitize email input
    $password = $_POST['password'];

    // Validate credentials
    if ($email === $admin_email && password_verify($password, $admin_password_hashed)) {
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);

        // Store login status in session
        $_SESSION['admin_logged_in'] = true;

        // Redirect to admin dashboard
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Campus Network</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        .navbar {
            background: #232f3e; /* Matching navbar color */
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
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 4rem);
            background-color: #d8e5d1; /* Matching background color */
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem; /* Adjust margin for better spacing */
        }
        form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 0.8rem;
            background: #232f3e; /* Matching button background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #016064; /* Matching hover effect */
        }
        .error-message {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
        footer {
            text-align: center;
            padding: 1rem;
            background: #232f3e; /* Matching footer background */
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .login-box {
           
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
    </style>
</head>
<body>


<main>


    <!-- Display error message -->
    <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
    <div class="login-box">
            <h1>Admin Login</h1>
    <form method="POST" action="adminlogin.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="Enter Admin Email">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="Enter Password">

        <button type="submit">Login</button>
    </form>
    </div>
</main>


</body>
</html>

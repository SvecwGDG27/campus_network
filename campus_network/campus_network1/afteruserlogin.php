<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Network</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .header nav ul li {
            display: inline;
        }

        .header nav ul li a {
            text-decoration: none;
            color: black;
            font-size: 16px;
            padding: 8px 16px;
            border: 1px solid transparent;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .header nav ul li a:hover {
            color: white;
            background-color: #016064;
            border-color: #016064;
        }

        .header .logo {
            height: 50px;
            cursor: pointer;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #d8e5d1;
            padding: 40px 20px;
        }

        main h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }

        main p {
            font-size: 18px;
            color: #555;
        }

        footer {
            background-color: #232f3e;
            color: white;
            padding: 20px 40px;
            text-align: center;
            font-size: 14px;
        }

        footer p {
            margin: 0;
            color: #ddd;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            transition: color 0.3s;
        }

        .header .logo:hover {
            color: #016064;
        }

    </style>
</head>
<body>
    <header class="header">
    <div class="logo">Campus Network</div>
        <nav>
            <ul>
                <li><a href="placement-calendar.php">Placement Calendar</a></li>
                <li><a href="interview-questions.php">Interview Questions</a></li>
                <li><a href="mock-interview.php">Daily practise</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="profile.php">Profile</a></li> <!-- Link to Profile Page -->
            </ul>
        </nav>
    </header>

    <main>
        <h1>Welcome to Campus Network</h1>
        <p>Navigate through the links to explore various features of our platform.</p>
    </main>

    <footer>
        <p>&copy; 2024 Campus Network. All rights reserved.</p>
    </footer>
</body>
</html>

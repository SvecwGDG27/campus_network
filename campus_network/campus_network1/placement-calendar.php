<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .heading {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .heading h1 {
            font-size: 3em;
            color: #1F2A22;
            margin: 0;
        }

        .heading p {
            font-size: 1.2em;
            color:  #016064;
            margin: 0;
        }

        .content {
    display: flex;
    width: 100%;
    justify-content: flex-start; /* Align items to the left */
    gap: 20px; /* Add space between elements */
}

        .calendar-container {
            flex: 1;
            text-align: center;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            max-width: 600px;
            margin: 0 auto;
        }

        .navigation {
            margin-bottom: 20px;
        }

        .navigation button {
            padding: 15px 30px;
            background-color: #0095b6;
            color: #000f89;
            border: none;
            cursor: pointer;
            font-size: 1.5em;
        }

        .month {
            grid-column: span 7;
            text-align: center;
            font-size: 3em;
            font-weight: bold;
            margin: 20px 0;
            color: #1F2A22;
        }

        .day {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
            width: 80px;
            background-color:#e7feff;
            color: #1F2A22;
            border: 1px solid #ccc;
            cursor: pointer;
            font-size: 1.5em;
            border-radius: 50%;
            margin: auto;
            position: relative;
        }

        .day .tooltip {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color:#007ba7;
            color:rgb(13, 13, 13);
            padding: 5px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .day:hover .tooltip {
            display: block;
        }

        .day.marked {
            background-color:#007ba7;
            color: #fff;
        }

        .day-name {
            font-weight: bold;
            color: #016064;
            font-size: 1.5em;
        }

        .event-details {
    flex: 0 0 250px;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    height: 200px;
    overflow: hidden;
    margin-right: 190px; /* Create the gap on the right side */
}

        .event-details h3 {
            font-size: 1.2em;
            color: #1F2A21;
            margin-bottom: 5px;
        }

        .event-details p {
            font-size: 0.9em;
            color: #016064;
            margin-bottom: 5px;
        }
        .calendar-container {
    flex: 1;
    text-align: center;
}
    </style>
</head>
<body>

<?php
// Connect to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campus_network1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch placement events
$sql = "SELECT * FROM placement_events";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[$row['event_date']] = $row;
}
$conn->close();
?>

    <div class="heading">
        <h1>Placement Calendar</h1>
</br>
    <b><p>Know When and Which Company is Coming!!</p></b>
    </div>

    <div class="content">
        <div class="calendar-container">
            <div class="navigation">
                <button onclick="prevMonth()">❮</button>
                <b><span id="monthYear"></span></b>
                <button onclick="nextMonth()">❯</button>
            </div>
            <div id="calendar" class="calendar"></div>
        </div>

        <div class="event-details">
            <h3>Company Details</h3>
            <p><strong>Company:</strong> <span id="companyName"></span></p>
            <p><strong>Rounds:</strong> <span id="rounds"></span></p>
            <p><strong>Round Types:</strong> <span id="roundTypes"></span></p>
        </div>
    </div>

    <script>
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();

        const interviewData = <?php echo json_encode($events); ?>;

        function generateCalendar(year, month) {
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            document.getElementById('monthYear').textContent = `${months[month]} ${year}`;

            for (let j = 0; j < days.length; j++) {
                const dayName = document.createElement('div');
                dayName.className = 'day-name';
                dayName.textContent = days[j];
                calendar.appendChild(dayName);
            }

            const firstDay = new Date(year, month, 1).getDay();
            for (let j = 0; j < firstDay; j++) {
                const blankDay = document.createElement('div');
                blankDay.className = 'day';
                calendar.appendChild(blankDay);
            }

            const daysInMonth = new Date(year, month + 1, 0).getDate();
            for (let j = 1; j <= daysInMonth; j++) {
                const day = document.createElement('div');
                day.className = 'day';
                day.textContent = j;

                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(j).padStart(2, '0')}`;
                if (interviewData[dateKey]) {
                    day.classList.add('marked');
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = interviewData[dateKey].company_name;
                    day.appendChild(tooltip);
                }

                day.addEventListener('click', () => {
                    if (interviewData[dateKey]) {
                        document.getElementById('companyName').textContent = interviewData[dateKey].company_name;
                        document.getElementById('rounds').textContent = interviewData[dateKey].rounds;
                        document.getElementById('roundTypes').textContent = interviewData[dateKey].round_types;
                    } else {
                        document.getElementById('companyName').textContent = '';
                        document.getElementById('rounds').textContent = '';
                        document.getElementById('roundTypes').textContent = '';
                    }
                });

                calendar.appendChild(day);
            }
        }

        function prevMonth() {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            generateCalendar(currentYear, currentMonth);
        }

        function nextMonth() {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            generateCalendar(currentYear, currentMonth);
        }

        generateCalendar(currentYear, currentMonth);
    </script>

</body>
</html>

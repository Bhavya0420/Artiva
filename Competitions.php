<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competitions | Artiva</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f7f7f7;
            color: #333;
            display: flex;
            flex-direction: column;
        }
        main{
            flex: 1;
        }

        header {
            text-align: center;
            background: #21a395;
            color: #fff;
            padding: 15px 0;
        }

        header h1 {
            margin: 0;
            font-size: 2.3em;
        }

        header p {
            margin: 10px 0 0;
            font-size: 1.2em;
        }

        .events-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .event-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .event-card:hover {
            transform: scale(1.05);
        }

        .event-card h3 {
            margin: 0;
            font-size: 1.5em;
            color: #444;
        }

        .event-card p {
            font-size: 1em;
            margin: 10px 0;
            color: #666;
        }

        .timer {
            font-weight: bold;
            color: #e74c3c;
            margin: 10px 0;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            font-size: 0.9em;
            color: #444;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background: #21a395;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s;
        }

        form button:hover {
            background: #218838;
        }

        footer {
            text-align: center;
            padding: 10px;
            background: #21a395;
            color: #fff;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <header>
        <h1>Upcoming Competitions</h1>
        <p>Showcase your creativity and participate in exciting challenges!</p>
    </header>

    <main>
        <div id="events-container" class="events-container">
            <?php
            // Database configuration
            $host = 'localhost';
            $dbname = 'artiva';
            $db_user = 'root';
            $db_pass = '';

            try {
                // Connect to the database
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Fetch all competitions
                $stmt = $pdo->query("SELECT id, title AS event_name, description, end_date AS end_time FROM competitions");
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Check if there are events
                if (count($events) > 0) {
                    // Loop through events and display them
                    foreach ($events as $event) {
                        echo '<div class="event-card">';
                        echo '<h3>' . htmlspecialchars($event['event_name']) . '</h3>';
                        echo '<p>' . htmlspecialchars($event['description']) . '</p>';
                        echo '<div class="timer" data-end-time="' . $event['end_time'] . '"></div>';
                        echo '<form action="upload_entry.php" method="post" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="competition_id" value="' . $event['id'] . '">';
                        echo '<label>';
                        echo '<span>Your Name:</span>';
                        echo '<input type="text" name="name" placeholder="Your Name" required>';
                        echo '</label>';
                        echo '<label>';
                        echo '<span>Your Email:</span>';
                        echo '<input type="email" name="email" placeholder="Your Email" required>';
                        echo '</label>';
                        echo '<label>';
                        echo '<span>Upload Image:</span>';
                        echo '<input type="file" name="entry_image" accept="image/*" required>';
                        echo '</label>';
                        echo '<button type="submit">Enter Competition</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No competitions available at the moment.</p>';
                }

            } catch (PDOException $e) {
                // Handle any errors that occur during the database connection or query execution
                echo '<p>Unable to fetch events. Please try again later.</p>';
            }
            ?>
        </div>
       <br><br><br><br><br><br><br>
    </main>

    <footer>
        <p>&copy; 2024 Artiva. All rights reserved.</p>
    </footer>

    <script>
        // Timer logic to countdown the remaining time for each competition
        document.addEventListener('DOMContentLoaded', () => {
            const timers = document.querySelectorAll('.timer');
            timers.forEach(timer => {
                const endTime = new Date(timer.getAttribute('data-end-time'));
                const updateTimer = () => {
                    const now = new Date();
                    const timeLeft = endTime - now;

                    if (timeLeft <= 0) {
                        timer.textContent = 'Competition Closed!';
                        clearInterval(intervalId);
                    } else {
                        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeLeft / (1000 * 60 * 60)) % 24);
                        const minutes = Math.floor((timeLeft / (1000 * 60)) % 60);
                        const seconds = Math.floor((timeLeft / 1000) % 60);

                        timer.textContent = `Time Left: ${days}d ${hours}h ${minutes}m ${seconds}s`;
                    }
                };

                const intervalId = setInterval(updateTimer, 1000);
                updateTimer();
            });
        });
        
        // Show alert if success parameter is present in URL
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                alert('Your entry has been successfully submitted!');
            }
        });
    
    </script>
</body>
</html>

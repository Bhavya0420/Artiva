<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change to your database username
$password = "";      // Change to your database password
$dbname = "artiva";   // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['u'];
    $password = $_POST['p'];  // Password entered by the user

    // Fetch user data from the database
    $sql = "SELECT id, username, password FROM usee WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found
        $row = $result->fetch_assoc();

        // Verify the password (you should use password_hash during registration for security)
        if ($password === $row['password']) {
            // Start session and store user details
            session_start();
            $_SESSION['user_id'] = $row['id'];       // Store user ID in session
            $_SESSION['username'] = $row['username']; // Store username in session

            // Redirect to homepage or dashboard
            header("Location: seller.html");
            exit();
        } else {
            // Incorrect password
            echo "Invalid password!";
        }
    } else {
        // User not found
        echo "User not found!";
    }
}

// Close the connection
$conn->close();
?>

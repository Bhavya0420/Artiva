<?php
// Database configuration
$host = 'localhost';     // Database host
$dbname = 'artiva';      // Database name
$db_user = 'root';       // Database username
$db_pass = '';           // Database password

session_start();  // Start the session to store user data if login is successful

// Get the username from the session
$username = $_SESSION['username'];
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Initialize error message variable
    $error = '';

    // Check if both username and password are provided
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        try {
            // Connect to the database using PDO
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL query to fetch user details by username
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            
            // Fetch user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Verify the password using password_hash()
                if (password_verify($password, $user['password'])) {
                    // If password matches, start the session and store user details
                    $_SESSION['user_id'] = $user['id'];       // Store user ID in session
                    $_SESSION['username'] = $user['username']; // Store username in session


                    
                    

                    // Redirect to homepage (or index.php)
                    header("Location: HomePageLogin.php"); 
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "User not found.";
            }
        } catch (PDOException $e) {
            // Handle any database connection errors
            $error = "Error: " . $e->getMessage();
        }
    }

    // Display error message if there is one
    if ($error) {
        echo "<p style='color: red;'>$error</p>";
    }
}
?>

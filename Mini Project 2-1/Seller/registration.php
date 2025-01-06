<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "artiva"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password']; // You may want to hash the password for security
    $gstinNumber = isset($_POST['gstinNumber']) ? $_POST['gstinNumber'] : NULL;
    $enrollmentId = isset($_POST['enrollmentId']) ? $_POST['enrollmentId'] : NULL;
    $accountName = $_POST['accountName'];
    $ifscCode = $_POST['ifscCode'];
    $accountNumber = $_POST['accountNumber'];

    // Insert data into database (ensure to sanitize and validate input before inserting)
    $sql = "INSERT INTO usee (fullName, email, mobile, address, username, password, gstinNumber, enrollmentId, accountName, ifscCode, accountNumber) 
            VALUES ('$fullName', '$email', '$mobile', '$address', '$username', '$password', '$gstinNumber', '$enrollmentId', '$accountName', '$ifscCode', '$accountNumber')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        // Redirect to home page after successful registration
        header("Location: ../HomePage.html ");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

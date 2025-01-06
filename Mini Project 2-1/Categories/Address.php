<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artiva";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip = $_POST['zip'] ?? '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zip)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Insert data into the database
    $sql = "INSERT INTO addresses (name, email, phone, address, city, state, zip) 
            VALUES ('$name', '$email', '$phone', '$address', '$city', '$state', '$zip')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to another page after successful submission
        header("Location: checkout.html"); // Replace with the page you want to redirect to
        exit; // Ensure no further code is executed after the redirect
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }

    $conn->close();
}
?>

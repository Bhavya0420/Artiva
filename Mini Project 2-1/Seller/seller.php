<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = "";     // Replace with your DB password
$dbname = "artiva"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $productName = isset($_POST['product-name']) ? trim($_POST['product-name']) : '';
    $productDescription = isset($_POST['product-description']) ? trim($_POST['product-description']) : '';
    $productPrice = isset($_POST['product-price']) ? floatval($_POST['product-price']) : 0;

    // Check if required fields are filled
    if (empty($productName) || empty($productDescription) || empty($productPrice) || $productPrice <= 0) {
        echo "Please fill all required fields and ensure price is valid.";
        exit;
    }

    // Handle file upload
    $targetDir = "../uploads/";

    // Check if the upload directory exists, if not, create it
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            echo "Failed to create upload directory.";
            exit;
        }
    }

    $fileName = basename($_FILES["product-image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Check if the file was uploaded without errors
    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] === UPLOAD_ERR_OK) {
        // Check if file is an image (optional validation step)
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (!in_array($fileType, $allowedTypes)) {
            echo "Only JPG, PNG, JPEG, and GIF files are allowed.";
            exit;
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["product-image"]["tmp_name"], $targetFilePath)) {
            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO product (productname, description, image, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssd", $productName, $productDescription, $targetFilePath, $productPrice);

            if ($stmt->execute()) {
                // Show success message and redirect to seller.html
                echo "<script>
                        alert('Product uploaded successfully!');
                        window.location.href = '../HomePageLogin.php';
                      </script>";
            } else {
                echo "Error inserting data: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "File upload failed. Please try again.";
        }
    } else {
        echo "Error with file upload: " . $_FILES['product-image']['error'];
    }
}

$conn->close();
?>

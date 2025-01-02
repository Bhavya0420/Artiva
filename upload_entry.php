<?php
// Ensure the 'uploads' directory exists and is writable
$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    // Attempt to create the uploads directory if it doesn't exist
    if (!mkdir($targetDir, 0777, true)) {
        die("Unable to create 'uploads' directory. Please check your folder permissions.");
    }
}

// Database configuration
$host = 'localhost';
$dbname = 'artiva';  // Change to your database name
$db_user = 'root';   // Change to your database username
$db_pass = '';       // Change to your database password

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle the file upload
function handleFileUpload($file) {
    global $targetDir;
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . uniqid() . "_" . $fileName;  // Ensure unique file names
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Debug: Print the target file path
    echo "Target file path: " . $targetFilePath . "<br>";

    // Validate file type (e.g., jpg, png, gif)
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $validExtensions)) {
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $targetFilePath; // Return the file path if upload is successful
        } else {
            echo "File upload failed. Please check directory permissions.<br>";
            return false;
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.<br>";
        return false;
    }
}

// Check if form is submitted and handle the file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["entry_image"])) {
    $file = $_FILES["entry_image"];
    
    // Call the upload function
    $uploadResult = handleFileUpload($file);
    
    if ($uploadResult) {
        // Successfully uploaded file, now save the file path to the database
        $filePath = $uploadResult;
        $name = htmlspecialchars($_POST['name']);  // Get the user's name from the form
        $email = htmlspecialchars($_POST['email']); // Get the user's email from the form
        $competitionId = $_POST['competition_id'];  // Get the competition ID
        
        // Insert file path into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO entries (competition_id, name, email, file_path, submission_time) 
                                   VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$competitionId, $name, $email, $filePath]);
            // Redirect to the homepage with success parameter
            header("Location: HomePageLogin.php");
            exit;  // Ensure the script stops after redirection
        } catch (PDOException $e) {
            echo "Error inserting into database: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Sorry, there was an issue with the image upload. Please make sure the file is an image and try again.";
    }
} else {
    echo "No file uploaded.";
}
?>

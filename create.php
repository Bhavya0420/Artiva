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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        // Insert competition into the database
        $stmt = $pdo->prepare("INSERT INTO competitions (title, description, start_date, end_date) VALUES (:title, :description, :start_date, :end_date)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);

        echo "Competition created successfully!";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

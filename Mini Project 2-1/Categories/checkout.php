<?php
// Enable CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database configuration
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "artiva";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate the input data
if (!isset($data['products']) || !isset($data['totalPrice'])) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

$products = $data['products'];
$totalPrice = $data['totalPrice'];
$orderDate = isset($data['orderDate']) ? $data['orderDate'] : date('Y-m-d H:i:s');  // Default to current date if not set

// Prepare and execute the insert query for each product
foreach ($products as $product) {
    $productName = $product['name'];
    $price = $product['price'];
    $quantity = isset($product['quantity']) ? $product['quantity'] : 1;  // Default to 1 if quantity is missing
    $image = $product['image'];

    // SQL query to insert product details into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (product_name, price, quantity, image, total_price, order_date)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $productName, $price, $quantity, $image, $totalPrice, $orderDate);

    if (!$stmt->execute()) {
        // Error occurred during the insert
        echo json_encode(["success" => false, "message" => "Failed to confirm order: " . $stmt->error]);
        $conn->close();
        exit;
    }
}

// Close the database connection
$conn->close();

// Return a success response
echo json_encode(["success" => true, "message" => "Order confirmed"]);
?>

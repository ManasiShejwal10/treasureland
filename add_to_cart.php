<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'treasureland_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to add items to your cart.";
        header("Location: login.php");
        exit();
    }

    // Get product ID from POST request
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = 1;  // Default quantity, adjust as necessary

    // Insert product into the cart with Order_Status as 'In Cart'
    $stmt = $pdo->prepare("INSERT INTO cart_details (Product_id, User_id, Quantity, Order_Status) 
                           VALUES (:product_id, :user_id, :quantity, 'In Cart')");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success'] = "Product added to your cart!";
    header("Location: cart.php");
    exit();
} catch (PDOException $e) {
    echo "<p class='text-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to proceed with checkout.";
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'treasureland_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_SESSION['user_id'];

    // Update Order_Status to 'Ordered' for all items in the user's cart
    $stmt = $pdo->prepare("UPDATE cart_details SET Order_Status = 'Ordered' WHERE User_id = :user_id AND Order_Status = 'In Cart'");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success'] = "Order placed successfully!";
    header("Location: shop.php");
    exit();
} catch (PDOException $e) {
    echo "<p class='text-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>

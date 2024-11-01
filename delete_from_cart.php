<?php
session_start();

// Check if an order ID was sent via POST
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    
    // Database connection
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the item from the cart for the given product_id and user
        $stmt = $pdo->prepare("DELETE FROM cart_details WHERE product_id = :product_id AND User_id = :user_id");
        $stmt->bindParam(':product_id', $_POST['product_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Item removed from cart successfully.";
        } else {
            $_SESSION['error'] = "Error removing item from cart.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . htmlspecialchars($e->getMessage());
    }

    // Redirect back to cart
    header("Location: cart.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: cart.php");
    exit();
}

<?php
session_start();

// Check if a product ID was sent via POST
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    // Database connection
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch product details
        $stmt = $pdo->prepare("SELECT product_id, product_name, price FROM product_details WHERE product_id = :id LIMIT 1");
        $stmt->bindParam(':id', $_POST['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if product exists
        if ($product) {
            // Check if user is logged in
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $product_id = $product['product_id'];
                $quantity = 1; // Default quantity

                // Insert order into cart_details table
                $insertStmt = $pdo->prepare("INSERT INTO cart_details (Product_id, User_id, Quantity) VALUES (:product_id, :user_id, :quantity)");
                $insertStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);

                if ($insertStmt->execute()) {
                    $_SESSION['success'] = "Added to cart successfully!";
                } else {
                    $_SESSION['error'] = "Error adding to cart.";
                }

                // Redirect to cart page
                header("Location: cart.php");
                exit();
            } else {
                // Redirect to login page if not logged in
                header("Location: login.php?id=" . $product['product_id']);
                exit();
            }
        } else {
            $_SESSION['error'] = "Product not found.";
            header("Location: shop.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . htmlspecialchars($e->getMessage());
        header("Location: shop.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: shop.php");
    exit();
}
?>

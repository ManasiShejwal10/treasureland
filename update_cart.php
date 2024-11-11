<?php
session_start();

// Check if product ID and action are sent via POST
if (isset($_POST['product_id']) && is_numeric($_POST['product_id']) && isset($_POST['action'])) {
    
    // Database connection
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please log in to modify your cart.";
            header("Location: login.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id'];
        $action = $_POST['action'];

        // Fetch the current quantity of the product in the cart
        $stmt = $pdo->prepare("
            SELECT Quantity FROM cart_details 
            WHERE User_id = :user_id AND Product_id = :product_id AND Order_Status = 'In Cart'
        ");
        $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $current_quantity = $result['Quantity'];

            if ($action === 'increase') {
                // Increase quantity by 1
                $stmt = $pdo->prepare("
                    UPDATE cart_details 
                    SET Quantity = Quantity + 1 
                    WHERE User_id = :user_id AND Product_id = :product_id AND Order_Status = 'In Cart'
                ");
                $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
                
            } elseif ($action === 'decrease' && $current_quantity > 1) {
                // Decrease quantity by 1 if more than 1
                $stmt = $pdo->prepare("
                    UPDATE cart_details 
                    SET Quantity = Quantity - 1 
                    WHERE User_id = :user_id AND Product_id = :product_id AND Order_Status = 'In Cart'
                ");
                $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
                
            } else {
                // If quantity is 1, remove the item from the cart
                $stmt = $pdo->prepare("
                    DELETE FROM cart_details 
                    WHERE User_id = :user_id AND Product_id = :product_id AND Order_Status = 'In Cart'
                ");
                $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
                $_SESSION['success'] = "Item removed from cart.";
            }
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

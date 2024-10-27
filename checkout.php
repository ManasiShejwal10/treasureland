<?php
session_start();

// Check if cart is not empty
if (!empty($_SESSION['cart'])) {
    // Clear the cart
    unset($_SESSION['cart']);
    
    // Display success message
    $_SESSION['success'] = "Order placed successfully!";
    header("Location: shop.php");
    exit();
} else {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: cart.php");
    exit();
}
?>

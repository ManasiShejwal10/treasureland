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

    // Fetch cart items for logged-in user
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        $stmt = $pdo->prepare("SELECT c.Order_id, p.product_name, c.Quantity, p.price FROM cart_details c JOIN product_details p ON c.Product_id = p.product_id WHERE c.User_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $cartItems = [];
    }
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Your Cart</title>
</head>
<body>
    <div class="container my-5">
        <h2>Your Cart</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty. <a href="shop.php">Continue shopping</a>.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($item['Quantity'] * $item['price'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

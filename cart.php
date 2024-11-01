<?php
session_start();

$host = 'localhost';
$dbname = 'treasureland_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to view your cart.";
        header("Location: login.php");
        exit();
    } else {
        $user_id = $_SESSION['user_id'];

        // Fetch unique items in cart, grouped by Product_id
        $stmt = $pdo->prepare("
            SELECT c.Product_id, p.product_name, SUM(c.Quantity) AS Quantity, p.price 
            FROM cart_details c 
            JOIN product_details p ON c.Product_id = p.product_id 
            WHERE c.User_id = :user_id AND c.Order_Status = 'In Cart' 
            GROUP BY c.Product_id
        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($cartItems) === 0) {
            $_SESSION['error'] = "Your cart is empty.";
        }
    }
} catch (PDOException $e) {
    echo "<p class='text-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Treasureland | Your Cart</title>
</head>
<body>

<h1 class="text-center my-4">Dive into history and unveil the treasures!</h1>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Home</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <span class="navbar-text">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Log in</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="shop.php">Shop</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="aboutus.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart.php">
                    <i class="fa-solid fa-cart-plus"></i> 
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container my-5">
    <h2>Your Cart</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (count($cartItems) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['Quantity'] * $item['price'], 2)); ?></td>
                        <td>
                            <form action="delete_from_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['Product_id']); ?>">
                                
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty. <a href="shop.php">Continue shopping</a>.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- Footer Section -->
<footer class="bg-dark text-light py-1">
    <div class="container">
        <div class="row">
            <!-- Contact Section -->
            <div class="col-md-4 mb-3 mt-3">
                <h5>Contact Us</h5>
                <p class="small mb-1"><i class="fas fa-map-marker-alt"></i> 123 Treasureland Street, City, Country</p>
                <p class="small mb-1"><i class="fas fa-phone"></i> +123 456 7890</p>
                <p class="small"><i class="fas fa-envelope"></i> support@treasureland.com</p>
            </div>
        </div>

        <!-- Copyright Section -->
        <div class="row">
            <div class="col mb-3 ">
                <p class="small mb-0">&copy; <?php echo date("Y"); ?> Treasureland. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Add FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>

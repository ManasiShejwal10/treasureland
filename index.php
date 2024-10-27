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

    // Fetch featured products from the database
    $stmt = $pdo->query("SELECT product_id, product_img, product_name, description, price FROM product_details LIMIT 3");
    $featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Treasureland | Home</title>
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
                    <a class="nav-link" href="logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Featured Products Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php else: ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-md-4 mb-4 product-card" data-name="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <div class="card shadow-sm">
                            <img src="<?php echo htmlspecialchars($product['product_img']); ?>" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                                <h6 class="text-success">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></h6>
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-block">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript for Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <title>Treasureland | Product Details</title>

    <h1 class="text-center my-4">Product Details</h1>
</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Treasureland Logo" style="height: 40px;">
            </a>
            <a class="navbar-brand text-white" href="index.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 30 30%27%3E%3Cpath stroke=%27rgba(255, 255, 255, 1)%27 stroke-width=%272%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3E%3C/svg%3E');"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-white dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item active">
                            <a class="nav-link text-white" href="login.php">Log in</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="aboutus.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="cart.php">
                            <i class="fa-solid fa-cart-plus"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php
        // Database connection details
        $host = 'localhost';
        $dbname = 'treasureland_db';
        $user = 'root';
        $pass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if 'id' is provided in the URL
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $product_id = (int) $_GET['id'];

                // Prepare a query to fetch product details by ID
                $stmt = $pdo->prepare("SELECT product_id, product_img, product_name, description, price, stock FROM product_details WHERE product_id = :id LIMIT 1");
                $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                $stmt->execute();

                // Check if the product exists
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    echo '<h2 class="text-center mb-4">' . htmlspecialchars($product['product_name']) . '</h2>';
                    echo '<div class="card mb-4 d-flex flex-sm-row align-items-center">';
                    echo ' <img src="' . htmlspecialchars($product['product_img'] ?? 'default.jpg') . '" class="w-50 card-img-top" alt="Product Image">';
                    echo ' <div class="card-body">';
                    echo ' <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>';
                    echo ' <p class="card-text">' . htmlspecialchars($product['description']) . '</p>';
                    echo ' <h6 class="text-success">Price: $' . htmlspecialchars(number_format($product['price'], 2)) . '</h6>';
                    
                    // Check if product is in stock
                    if ($product['stock'] == 0) {
                        echo '<p class="text-danger">This product is currently out of stock.</p>';
                        echo ' <button type="button" class="btn btn-secondary btn-block mt-3" disabled>Out of Stock</button>';
                    } else {
                        // Add to Cart button for available items
                        echo ' <form action="add_to_cart.php" method="POST">';
                        echo ' <input type="hidden" name="product_id" value="' . htmlspecialchars($product['product_id']) . '">';
                        
                        // Check if user is logged in
                        if (isset($_SESSION['user_id'])) {
                            echo ' <button type="submit" class="btn btn-success btn-block mt-3">Add to Cart</button>';
                        } else {
                            echo ' <a href="login.php?id=' . $product['product_id'] . '" class="btn btn-primary btn-block mt-3">Log In to Add to Cart</a>';
                        }

                        echo ' </form>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '<a href="shop.php" class="btn btn-secondary">Back to Shop</a>';
                } else {
                    echo "<p class='text-danger'>Product not found or it may have been removed.</p>";
                }
            } else {
                echo "<p class='text-danger'>Invalid product ID.</p>";
            }

        } catch (PDOException $e) {
            echo "<p class='text-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- Footer Section -->
    <footer class="bg-dark text-light py-1">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mt-3">
                    <h5>Contact Us</h5>
                    <p class="small mb-1"><i class="fas fa-map-marker-alt"></i> 123 Treasureland Street, City, Country</p>
                    <p class="small mb-1"><i class="fas fa-phone"></i> +123 456 7890</p>
                    <p class="small"><i class="fas fa-envelope"></i> support@treasureland.com</p>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <p class="small mb-0">© <?php echo date("Y"); ?> Treasureland. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>

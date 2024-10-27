<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Product Details</title>
</head>
<body>
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
                $stmt = $pdo->prepare("SELECT product_id, product_img, product_name, description, price FROM product_details WHERE product_id = :id LIMIT 1");
                $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                $stmt->execute();

                // Check if the product exists
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    echo '<h2 class="text-center mb-4">' . htmlspecialchars($product['product_name']) . '</h2>';
                    echo '<div class="card mb-4">';
                    echo '    <img src="' . htmlspecialchars($product['product_img'] ?? 'default.jpg') . '" class="w-50 card-img-top" alt="Product Image">';
                    echo '    <div class="card-body">';
                    echo '        <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>';
                    echo '        <p class="card-text">' . htmlspecialchars($product['description']) . '</p>';
                    echo '        <h6 class="text-success">Price: $' . htmlspecialchars(number_format($product['price'], 2)) . '</h6>';
                    echo '        <form action="add_to_cart.php" method="POST">';
                    echo '            <input type="hidden" name="product_id" value="' . htmlspecialchars($product['product_id']) . '">';

                    // Check if user is logged in
                    if (isset($_SESSION['user_id'])) {
                        echo '            <button type="submit" class="btn btn-success btn-block mt-3">Add to Cart</button>';
                    } else {
                        // Redirect to login page if not logged in
                        echo '            <a href="login.php?id=' . $product['product_id'] . '" class="btn btn-primary btn-block mt-3">Log In to Add to Cart</a>';
                    }

                    echo '        </form>';
                    echo '    </div>';
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
</body>
</html>

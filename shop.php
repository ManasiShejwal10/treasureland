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
    <title>Treasureland | Shop</title>
    <style>

    .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
    }
    .dropdown-item {
        cursor: pointer;
    }
    .product-card .card-img-top {
        height: 200px; /* Set a fixed height for images */
        object-fit: cover; /* Crop images to fill the area without stretching */
        width: 100%;
    }
    .product-card .card {
        height: 100%; /* Make all cards the same height */
        display: flex;
        flex-direction: column;
    }
    .product-card .card-body {
        flex-grow: 1; /* Ensures card-body takes up remaining space in the card */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-card .card-title {
        font-size: 1.1em;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap; /* Prevents text from wrapping */
        text-overflow: ellipsis; /* Adds "..." for long text */
    }
    .product-card .card-text {
        font-size: 0.9em;
        color: #6c757d;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Limit description to 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .product-card .text-success {
        font-size: 1em;
        font-weight: 600;
    }

    </style>
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
                <a class="nav-link" href="aboutus.php">About us</a>
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <div class="row" id="product-container">
            <?php
            // Database connection details
            $host = 'localhost';
            $dbname = 'treasureland_db';
            $user = 'root';
            $pass = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Fetch products from the database
                $stmt = $pdo->query("SELECT product_id, product_img, product_name, description, price FROM product_details");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through products and display each as a card
                foreach ($products as $product) {
                    echo '<div class="col-md-4 mb-4 product-card " data-name="' . htmlspecialchars($product['product_name']) . '">';
                    echo '    <div class="card shadow-sm ">';
                    echo '        <img src="' . htmlspecialchars($product['product_img']) . '" class="card-img-top" alt="Product Image" >';
                    echo '        <div class="card-body">';
                    echo '            <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>';
                    // echo '            <p class="card-text text-muted">' . htmlspecialchars($product['description']) . '</p>';
                    echo '            <h6 class="text-success">$' . htmlspecialchars(number_format($product['price'], 2)) . '</h6>';
                    echo '            <a href="product_details.php?id=' . $product['product_id'] . '" class="btn btn-primary btn-block">View Details</a>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }

            } catch (PDOException $e) {
                echo "<p class='text-danger'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const productCards = document.querySelectorAll('.product-card');

        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim().toLowerCase();
            dropdownMenu.innerHTML = '';

            if (query) {
                productCards.forEach(card => {
                    const productName = card.getAttribute('data-name').toLowerCase();
                    if (productName.includes(query)) {
                        const item = document.createElement('div');
                        item.className = 'dropdown-item';
                        item.textContent = card.getAttribute('data-name');
                        item.onclick = () => {
                            searchInput.value = item.textContent;
                            filterProducts(item.textContent.toLowerCase());
                            dropdownMenu.innerHTML = '';
                        };
                        dropdownMenu.appendChild(item);
                    }
                });
                dropdownMenu.classList.add('show');
            } else {
                dropdownMenu.classList.remove('show');
                showAllProducts();
            }
        });

        document.addEventListener('click', function (e) {
            if (!dropdownMenu.contains(e.target) && e.target !== searchInput) {
                dropdownMenu.classList.remove('show');
            }
        });

        function filterProducts(query) {
            productCards.forEach(card => {
                const productName = card.getAttribute('data-name').toLowerCase();
                if (productName.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function showAllProducts() {
            productCards.forEach(card => {
                card.style.display = 'block';
            });
        }
    });
    </script>
</body>

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

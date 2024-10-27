<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Treasureland | Shop</title>
    <style>
        .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
        }
        .dropdown-item {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 class="text-center my-4">Dive into history and unveil the treasures!</h1>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Home</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Log in</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aboutus.php">About us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log out</a>
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
                    echo '<div class="col-md-4 mb-4 product-card" data-name="' . htmlspecialchars($product['product_name']) . '">';
                    echo '    <div class="card shadow-sm">';
                    echo '        <img src="' . htmlspecialchars($product['product_img']) . '" class="card-img-top" alt="Product Image">';
                    echo '        <div class="card-body">';
                    echo '            <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>';
                    echo '            <p class="card-text text-muted">' . htmlspecialchars($product['description']) . '</p>';
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
</html>

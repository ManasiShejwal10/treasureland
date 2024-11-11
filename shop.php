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

    <!-- Updated Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Treasureland | Shop</title>

    <h1 class="text-center my-4">Enjoy Shopping!</h1>

    <style>
        .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
            display: none;
            position: absolute;
            z-index: 1000;
        }

        .dropdown-item {
            cursor: pointer;
        }

        .product-card .card-img-top {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .product-card .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-card .card-title {
            font-size: 1.1em;
            font-weight: bold;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .product-card .card-text {
            font-size: 0.9em;
            color: #6c757d;
            display: -webkit-box;
            -webkit-line-clamp: 2;
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

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Treasureland Logo" style="height: 40px;"> <!-- Adjust the height as needed -->
            </a>
            <a class="navbar-brand text-white" href="index.php">Home</a>

            <!-- Add the Search Form in the Navbar -->
            <form class="d-flex ml-auto w-25 w-sm-auto">
                <input class="form-control me-2 w-100 w-sm-50 w-md-25" type="search" id="search-input" placeholder="Search" aria-label="Search">
                <div class="dropdown-menu" id="dropdown-menu"></div> <!-- Dropdown menu for search suggestions -->
            </form>

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
            <div class="alert alert-success"><?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="row " id="product-container">
            <?php
            try {
                // Fetch products from the database
                $stmt = $pdo->query("SELECT product_id, product_img, product_name, description, price FROM product_details");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through products and display each as a card
                foreach ($products as $product) {
                    echo '<div class="col-md-3 mb-3 product-card" data-name="' . htmlspecialchars($product['product_name']) . '">';
                    echo ' <div class="card shadow-sm">';
                    echo ' <img src="' . htmlspecialchars($product['product_img']) . '" class="card-img-top" alt="Product Image">';
                    echo ' <div class="card-body">';
                    echo ' <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>';
                    echo ' <h6 class="text-success">$' . htmlspecialchars(number_format($product['price'], 2)) . '</h6>';
                    echo ' <a href="product_details.php?id=' . $product['product_id'] . '" class="btn btn-primary btn-block">View Details</a>';
                    echo ' </div>';
                    echo ' </div>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo "<p class='text-danger'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </div>

    <!-- JavaScript includes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const dropdownMenu = document.getElementById('dropdown-menu');
            const productCards = document.querySelectorAll('.product-card'); // All the product cards

            // Event listener for search input field
            searchInput.addEventListener('input', function () {
                const query = searchInput.value.trim().toLowerCase(); // Get the search query

                dropdownMenu.innerHTML = ''; // Clear previous dropdown suggestions
                dropdownMenu.style.display = 'none'; // Hide dropdown by default

                if (query) {
                    // Filter through the product cards
                    productCards.forEach(card => {
                        const productName = card.getAttribute('data-name').toLowerCase(); // Get the product name

                        // If the product name includes the query
                        if (productName.includes(query)) {
                            const item = document.createElement('div');
                            item.className = 'dropdown-item';
                            item.textContent = card.getAttribute('data-name');
                            item.onclick = () => {
                                // Set search input to selected item
                                searchInput.value = item.textContent;
                                filterProducts(item.textContent.toLowerCase()); // Filter products by selected item
                                dropdownMenu.innerHTML = ''; // Clear dropdown
                                dropdownMenu.style.display = 'none'; // Hide dropdown
                            };

                            dropdownMenu.appendChild(item); // Add item to dropdown
                            dropdownMenu.style.display = 'block'; // Show dropdown
                        }
                    });
                } else {
                    showAllProducts(); // Show all products if the search query is empty
                }
            });

            // Event listener to close dropdown if clicked outside
            document.addEventListener('click', function (e) {
                if (!dropdownMenu.contains(e.target) && e.target !== searchInput) {
                    dropdownMenu.style.display = 'none'; // Hide dropdown
                }
            });

            // Function to filter products based on the search query
            function filterProducts(query) {
                productCards.forEach(card => {
                    const productName = card.getAttribute('data-name').toLowerCase();
                    card.style.display = productName.includes(query) ? 'block' : 'none'; // Show or hide product card
                });
            }

            // Function to show all products
            function showAllProducts() {
                productCards.forEach(card => {
                    card.style.display = 'block'; // Display all products
                });
                dropdownMenu.style.display = 'none'; // Hide dropdown
            }
        });
    </script>

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

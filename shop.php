<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Treasureland | Shop</title>
    <style>
        /* Custom styles for the dropdown */
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

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Search form with dropdown -->
        <form class="form-inline my-2 my-lg-0 position-relative" autocomplete="off">
            <input id="search-input" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <div id="dropdown-menu" class="dropdown-menu position-absolute w-100"></div>
            <button class="btn btn-outline-success my-2 my-sm-0" type="button">Search</button>
        </form>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Log in <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aboutus.php">About us</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Product Card Group -->
    <div class="container my-5">
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

    <!-- JavaScript for Bootstrap and Search Functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz" crossorigin="anonymous"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const productCards = document.querySelectorAll('.product-card');

        // Display dropdown with matching product names
        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim().toLowerCase();
            dropdownMenu.innerHTML = '';

            // If the input is not empty, show dropdown items that match
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
                showAllProducts(); // Show all products when search is cleared
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdownMenu.contains(e.target) && e.target !== searchInput) {
                dropdownMenu.classList.remove('show');
            }
        });

        // Filter products based on the selected product name
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

        // Show all products when the input is cleared
        function showAllProducts() {
            productCards.forEach(card => {
                card.style.display = 'block';
            });
        }
    });
    </script>
</body>
</html>

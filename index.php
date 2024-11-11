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
    $stmt = $pdo->query("SELECT product_id, product_img, product_name, description, price FROM product_details WHERE Stock=1 LIMIT 3");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Treasureland | Home</title>
    <h1 class="text-center my-4">Welcome to Treasureland!</h1>
</head>
<style>
    .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
    }

    .dropdown-item {
        cursor: pointer;
    }

    .product-card .card-img-top {
        height: 200px;
        /* Set a fixed height for images */
        object-fit: cover;
        /* Crop images to fill the area without stretching */
        width: 100%;
    }

    .product-card .card {
        height: 100%;
        /* Make all cards the same height */
        display: flex;
        flex-direction: column;
    }

    .product-card .card-body {
        flex-grow: 1;
        /* Ensures card-body takes up remaining space in the card */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card .card-title {
        font-size: 1.1em;
        font-weight: bold;
        overflow: hidden;
        white-space: nowrap;
        /* Prevents text from wrapping */
        text-overflow: ellipsis;
        /* Adds "..." for long text */
    }

    .product-card .card-text {
        font-size: 0.9em;
        color: #6c757d;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Limit description to 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-card .text-success {
        font-size: 1em;
        font-weight: 600;
    }
</style>

<body>
    

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="logo.png" alt="Treasureland Logo" style="height: 40px;"> <!-- Adjust the height as needed -->
        </a>
        <a class="navbar-brand text-white" href="index.php">Home</a>
            <button class="navbar-toggler " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 30 30%27%3E%3Cpath stroke=%27rgba(255, 255, 255, 1)%27 stroke-width=%272%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3E%3C/svg%3E');"></span>

            </button>
            <div class="collapse navbar-collapse " id="navbarNavDropdown">
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

   
    <!--Carousel Section-->
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://www.soane.org/sites/default/files/banners/Photograph-Picture-Room-Soane-Museum-with-Canaletto-Hogarth-visible.jpg" class="d-block w-100" alt="..." style="height: 300px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="https://www.ancientsculpturegallery.com/wp-content/themes/ancientsculpturegallery/assets/images/b3.jpg" class="d-block w-100" alt="..." style="height: 300px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="https://www.ancientsculpturegallery.com/wp-content/themes/ancientsculpturegallery/assets/images/b6.jpg" class="d-block w-100" alt="..." style="height: 300px; object-fit: cover;">
            </div>
        </div>
        <button class="carousel-control-prev bg-transparent border-0" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden ">Previous</span>
        </button>
        <button class="carousel-control-next bg-transparent border-0" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <style>
        /* Additional CSS to adjust the carousel height */
        .carousel-inner img {
            height: 300px;
            /* Set your desired height here */
            object-fit: cover;
            /* Ensures the image covers the area without distortion */
        }
    </style>



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
                                <!-- <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p> -->
                                <h6 class="text-success">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></h6>
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-block">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- See More Button -->
        <div class="text-center mt-4">
            <a href="shop.php" class="btn btn-success">See More</a>
        </div>
    </div>

    <!-- JavaScript for Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

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

</html>
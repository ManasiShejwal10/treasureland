<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasureland | About us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
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
                <a class="nav-link" href="cart.php">
                    <i class="fa-solid fa-cart-plus"></i> 
                </a>
            </li>
        </ul>
    </div>
</nav>


    <section id="about" class="my-5">
        <div id="aboutus" class="container">
            <h2 class="text-center mb-4">About Us</h2>
            <p class="lead text-center">
                Welcome to Treasure Land! our website with replicas of museum artifacts, where the past is brought to life! In order to preserve the rich tapestry of human history, we are committed to offering a marketplace for the sale of genuine and distinctive artifacts from many cultures and historical periods.
            </p>
            <p>Our goal is to provide authentic artifacts that tell historical tales to the general public, history buffs, and collectors. To guarantee that our clients only receive the highest caliber pieces, every item in our collection has been meticulously sourced and verified.</p>
            <p>Through our carefully chosen collection, we hope to promote an appreciation for history and uphold the value of cultural heritage.</p>
            <p>Join us on this journey through time as we explore the wonders of human achievement and creativity. Thank you for visiting our site, and we hope you find something truly special that resonates with you!</p>
            <h3 class="mt-5">Our Values</h3>
            <ul>
                <li><strong>Authenticity:</strong> We ensure that every artifact is genuine and properly documented.</li>
                <li><strong>Education:</strong> We strive to educate our customers about the history and significance of each item.</li>
                <li><strong>Community:</strong> We believe in building a community of collectors and history lovers.</li>
                <li><strong>Preservation:</strong> We are committed to preserving cultural heritage for future generations.</li>
            </ul>
        </div>
    </section>

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

<!-- Add FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>

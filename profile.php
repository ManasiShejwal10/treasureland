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

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to view your profile.";
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch user details
    $stmt = $pdo->prepare("SELECT User_name, Email, Address FROM user_details WHERE User_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle form submission for updating address
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_address = $_POST['address'];

        // Update the address in the database
        $updateStmt = $pdo->prepare("UPDATE user_details SET Address = :address WHERE User_id = :user_id");
        $updateStmt->bindParam(':address', $new_address);
        $updateStmt->bindParam(':user_id', $user_id);
        $updateStmt->execute();

        $_SESSION['success'] = "Address updated successfully!";

        // Check if redirected from checkout
        if (isset($_GET['redirect']) && $_GET['redirect'] == 'checkout') {
            header("Location: checkout.php"); // Redirect back to checkout after saving address
        } else {
            header("Location: profile.php"); // Redirect to profile page to avoid resubmission
        }
        exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <title>Treasureland | Your Profile</title>
    <h1 class="text-center my-4">Your Profile</h1>
</head>

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

    <div class="container my-5">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($userDetails['User_name']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userDetails['Email']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($userDetails['Address']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
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
</body>
</html>

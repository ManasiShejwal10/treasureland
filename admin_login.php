<?php
session_start(); // Ensure session_start() is at the top of the file

// Initialize error message variable and redirect URL
$errorMessage = '';
$redirectURL = './admin_dashboard.php'; // Default redirect URL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Capture redirect URL if provided in POST request
    if (isset($_POST['redirectURL']) && !empty($_POST['redirectURL'])) {
        $redirectURL = trim($_POST['redirectURL']);
    }

    // Database connection details
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute query to fetch user details
        $stmt = $pdo->prepare("SELECT User_id, User_name, Password FROM user_details WHERE Email = :email AND User_category = 'admin' LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password if user exists
        if ($user && password_verify($password, $user['Password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $user['User_id'];
            $_SESSION['user_name'] = $user['User_name'];
            $_SESSION['success'] = "Welcome, " . $user['User_name'] . "! You have successfully logged in.";

            // Debugging: Check if we're reaching the redirection point
            error_log("Login successful. Redirecting to: " . $redirectURL);
            
            // Redirect to the specified page
            header("Location: " . $redirectURL);
            exit(); // Ensure script stops executing after redirection
        } else {
            // Invalid login credentials
            $errorMessage = "Invalid email or password.";
            error_log($errorMessage); // Log error for debugging
        }
    } catch (PDOException $e) {
        // Database connection or query error
        $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
        error_log($errorMessage); // Log database error
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Login | Treasureland</title>
</head>
<body>

<h1 class="text-center my-4">Log In to Your Account Admin</h1>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark ">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="logo.png" alt="Treasureland Logo" style="height: 40px;">
        </a>
        <!-- <a class="navbar-brand text-white" href="index.php">Home</a> -->
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
                            <li><a class="dropdown-item" href="admin_logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
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
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <input type="hidden" name="redirectURL" value="<?php echo htmlspecialchars($redirectURL); ?>">
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

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
                <p class="small mb-0">&copy; <?php echo date("Y"); ?> Treasureland. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</html>

<?php
session_start();

// Initialize error message variable
$errorMessage = '';

// Capture the URL of the page the user came from, if not already set
$redirectURL = isset($_GET['redirect']) ? $_GET['redirect'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'shop.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $redirectURL = trim($_POST['redirectURL']); // Capture redirect URL from form submission

    // Database connection details
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a query to fetch user details by email
        $stmt = $pdo->prepare("SELECT User_id, User_name, Password FROM user_details WHERE Email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password if user exists
        if ($user && password_verify($password, $user['Password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $user['User_id'];
            $_SESSION['user_name'] = $user['User_name'];
            $_SESSION['success'] = "Welcome, " . $user['User_name'] . "! You have successfully logged in.";

            // Redirect to the captured page or default to shop.php
            header("Location: $redirectURL");
            exit();
        } else {
            // Display error message for invalid credentials
            $errorMessage = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
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
    <h1 class="text-center my-4">Log in to Treasureland</h1>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Home</a>
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
                <div class="extra-links mt-3">
                    <a href="register.php?redirect=<?php echo urlencode($redirectURL); ?>">Don't have an account? Register here!</a>
                </div>
            </div>
        </div>
    </div>

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

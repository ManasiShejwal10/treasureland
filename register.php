<?php
session_start();

// Initialize an error message variable
$errorMessage = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Database connection details
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_details WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();

        if ($userExists) {
            $errorMessage = "An account with this email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO user_details (User_name, Email, Password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                $successMessage = "Registration successful! You can now log in.";
                header("Location: login.php"); // Redirect to login page
                exit();
            } else {
                $errorMessage = "An error occurred during registration. Please try again.";
            }
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
    <title>Register | Treasureland</title>
</head>
<body>
    <h1 class="text-center my-4">Create an Account</h1>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Home</a>
    </nav>

    <div class="container my-5">
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php elseif ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <div class="extra-links mt-3">
                    <a href="login.php">Already have an account? Log in here!</a>
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

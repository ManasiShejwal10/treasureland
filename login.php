<?php
session_start();

// Initialize error message variable
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user input and sanitize
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  echo 'SELECT User_id, User_name, Password FROM user_details WHERE Email = "'.$email.'" LIMIT 1';
  
  // Database connection details
    $host = 'localhost';
    $dbname = 'treasureland_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a query to fetch user details by email
        // bindParam(':email', $email);
        $stmt = $pdo->prepare('SELECT User_id, User_name, Password FROM user_details WHERE Email = "'.$email.'" LIMIT 1');
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password if user exists
        if ($user && password_verify($password, $user['Password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $user['User_id'];
            $_SESSION['user_name'] = $user['User_name'];
            $_SESSION['success'] = "Welcome, " . $user['User_name'] . "! You have successfully logged in.";

            // Redirect to the shop or home page
            header("Location: shop.php");
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
                    <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </form>
                <div class="extra-links mt-3">
                    <a href="register.php">Don't have an account? Register here!</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

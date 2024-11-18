<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'treasureland_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to proceed with checkout.";
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch user details (Name and Address)
    $stmt = $pdo->prepare("SELECT User_name, Address FROM user_details WHERE User_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch cart items
    $stmt = $pdo->prepare("
        SELECT c.Product_id, p.product_name, c.Quantity, p.price 
        FROM cart_details c 
        JOIN product_details p ON c.Product_id = p.product_id 
        WHERE c.User_id = :user_id AND c.Order_Status = 'In Cart'
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate the grand total
    $grandTotal = 0;
    foreach ($cartItems as $item) {
        $grandTotal += $item['price'] * $item['Quantity'];
    }

    // Process the order if the form is submitted
    $orderPlaced = false; // To track if the order is placed successfully
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the data from the form
        $receiver_name = $_POST['receiver_name'];
        $receiver_address = $_POST['receiver_address'];
        $phone_number = $_POST['phone_number'];

        // Update cart items to mark them as 'Ordered' and store receiver details
        foreach ($cartItems as $item) {
            // Update cart details with order information
            $stmt = $pdo->prepare("UPDATE cart_details 
                                   SET Receiver_name = :receiver_name, 
                                       Receiver_address = :receiver_address, 
                                       Phone_number = :phone_number, 
                                       Order_Status = 'Ordered' 
                                   WHERE User_id = :user_id AND Product_id = :product_id");
            $stmt->bindParam(':receiver_name', $receiver_name);
            $stmt->bindParam(':receiver_address', $receiver_address);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['Product_id'], PDO::PARAM_INT);
            $stmt->execute();
        }

        $orderPlaced = true;
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
    <link rel="stylesheet" href="css/style.css">
    <title>Treasureland | Checkout</title>
    <h1 class="text-center my-4">Checkout</h1>

</head>
<body>


 <!-- Navigation bar -->
 <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark mb-1">
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

    
    <!-- Display any error message -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Checkout Form -->
    <div class="container mt-4 mb-4">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
    <form id="checkoutForm" action="checkout.php" method="POST">
       <div class="form-group">
            <label for="receiver_name">Receiver's Name</label>
            <input type="text" class="form-control" id="receiver_name" name="receiver_name" required>
        </div>
        <div class="form-group">
            <label for="receiver_address">Receiver's Address</label>
            <input type="text" class="form-control" id="receiver_address" name="receiver_address" required>
            <input type="checkbox" id="copy_address" name="copy_address"> Use saved address (<?php echo htmlspecialchars($userDetails['Address']); ?>)
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <input type="text" class="form-control" id="payment_method" name="payment_method" value="Cash on Delivery" readonly>
        </div>
        <h5>Products in Your Cart</h5>
        <?php foreach ($cartItems as $item): ?>
            <input type="hidden" name="product_ids[]" value="<?php echo htmlspecialchars($item['Product_id']); ?>">
            <input type="hidden" name="quantities[]" value="<?php echo htmlspecialchars($item['Quantity']); ?>">
            <p><?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo htmlspecialchars($item['Quantity']); ?>) - $<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
        <?php endforeach; ?> 
        <button type="submit" class="btn btn-success btn-block">Place Order</button>
        </div>
        </div>
        </div>
        </form>


<?php if ($orderPlaced): ?>

<!-- Modal -->
<div class="modal fade show d-flex justify-content-center align-items-center" 
     id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true" 
     style="display: block; background: rgba(0, 0, 0, 0.5); z-index: 1050;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Order Placed Successfully!</h5>
      </div>
      <div class="modal-body">
        <p><strong>Receiver's Name:</strong> <?php echo htmlspecialchars($receiver_name); ?></p>
        <p><strong>Receiver's Address:</strong> <?php echo htmlspecialchars($receiver_address); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
        <p><strong>Payment Method:</strong> Cash on Delivery</p>
        <p><strong>Grand Total:</strong> $<?php echo number_format($grandTotal, 2); ?></p>
        <p class="mt-4">Thank you for shopping with us! Your order will be at your doorstep soon!</p>
      </div>
      <div class="modal-footer">
        <a href="shop.php" class="btn btn-success">Continue Shopping</a>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>

<script>
    // JavaScript to auto-fill address if checkbox is ticked
    document.getElementById('copy_address').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('receiver_address').value = '<?php echo addslashes($userDetails['Address']); ?>';
        } else {
            document.getElementById('receiver_address').value = '';
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


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
<?php
session_start();

// Uncomment the following lines if admin login restriction is needed
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: admin_login.php");
//     exit();
// }

// Database connection details
$host = 'localhost';
$dbname = 'treasureland_db';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch products based on filter if provided
$productFilter = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_name'])) {
    $productFilter = $_GET['product_name'];
    $stmt = $pdo->prepare("SELECT * FROM product_details WHERE product_name LIKE :product_name");
    $stmt->execute([':product_name' => '%' . $productFilter . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM product_details");
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all orders with "Ordered" status
$ordersStmt = $pdo->query("SELECT * FROM cart_details WHERE Order_Status = 'Ordered'");
$orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

// Add new product logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $productName = $_POST['product_name'];
    $product_img = $_POST['product_img'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("INSERT INTO product_details (product_name, product_img, price, description, stock) VALUES (:product_name, :product_img, :price, :description, :stock)");
    $stmt->execute([
        ':product_name' => $productName,
        ':product_img' => $product_img,
        ':price' => $price,
        ':description' => $description,
        ':stock' => $stock
    ]);
    header("Location: admin_dashboard.php");
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
    <title>Admin Dashboard | Treasureland</title>
    <style>
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .scrollable-table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 2;
        }
        
    </style>
</head>
<body>

    <h1 class="text-center my-4">Welcome, Admin!</h1>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Treasureland Logo" style="height: 40px;">
            </a>
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
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="admin_logout.php">Log out</a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add New Product Form -->
    <div class="container my-5">
        <h3 class="text-center">Add New Product</h3>
        <div class="card w-50 mx-auto">
            <form method="POST" class="form-group p-4" action="">
                <input type="hidden" name="add_product">
                <div class="form-group">
                    <label>Product Name:</label>
                    <input type="text" name="product_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Product Image URL:</label>
                    <input type="text" name="product_img" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Stock:</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>

    <!-- Product List with Filter -->
    <h3 class="text-center">Inventory</h3>
    <div class="container my-5">
        <form method="GET" action="" class="form-inline mb-3">
            <label for="product_name" class="mr-2">Filter by Product Name:</label>
            <input type="text" id="product_name" name="product_name" class="form-control mr-2" value="<?php echo htmlspecialchars($productFilter); ?>">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>

        <div class="scrollable-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Description</th>
                        <th scope="col">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['Product_id']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($product['Product_img']); ?>" target="_blank">Product Image</a></td>
                            <td><?php echo htmlspecialchars($product['Product_name']); ?></td>
                            <td>$<?php echo htmlspecialchars($product['Price']); ?></td>
                            <td><?php echo htmlspecialchars($product['Description']); ?></td>
                            <td><?php echo htmlspecialchars($product['Stock']) ? 'In Stock' : 'Out of Stock'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
            <div class="col mb-3 ">
                <p class="small mb-0">&copy; <?php echo date("Y"); ?> Treasureland. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

</html>

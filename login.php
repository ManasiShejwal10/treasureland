<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Treasureland | Log In</title>
    <style>
      /* Page Styling */
      body {
          background-color: #f8f9fa;
      }
      .login-container {
          max-width: 400px;
          padding: 2rem;
          background-color: #fff;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
          border-radius: 8px;
          margin: 100px auto;
      }
      .login-container h2 {
          text-align: center;
          margin-bottom: 1.5rem;
          color: #333;
      }
      .btn-primary {
          background-color: #28a745;
          border: none;
      }
      .btn-primary:hover {
          background-color: #218838;
      }
      .extra-links {
          text-align: center;
          margin-top: 1rem;
      }
      .extra-links a {
          color: #007bff;
          text-decoration: none;
      }
      .extra-links a:hover {
          text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1 class="text-center my-4">Dive into history and unveil the treasures!</h1>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="index.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="shop.php">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="aboutus.php">About us</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Login Form -->
    <div class="login-container">
        <h2>Log in to Treasureland</h2>
        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <!-- Additional Links -->
        <div class="extra-links mt-3">
            <a href="/forgot-password">Forgot Password?</a> |
            <a href="register.php">Create an Account</a>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
  </body>
</html>

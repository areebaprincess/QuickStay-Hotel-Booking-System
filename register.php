<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Registration - QuickStay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<?php include('inc/header.php'); ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8 bg-white p-4 rounded shadow-sm">
      <h4 class="mb-4 text-center fw-bold">User Registration</h4>

      <?php
      if (isset($_GET['error'])) {
          echo '<div class="alert alert-danger">'.htmlspecialchars($_GET['error']).'</div>';
      }
      if (isset($_GET['success'])) {
          echo '<div class="alert alert-success">'.htmlspecialchars($_GET['success']).'</div>';
      }
      ?>

      <form method="POST" action="register_user.php">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control shadow-none" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control shadow-none" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Phone Number</label>
            <input type="number" name="phone" class="form-control shadow-none" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control shadow-none" required>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control shadow-none" rows="2" required></textarea>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Pincode</label>
            <input type="number" name="pincode" class="form-control shadow-none" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control shadow-none" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control shadow-none" required>
          </div>
        </div>

        <div class="text-center my-3">
          <button type="submit" class="btn btn-dark shadow-none">Register</button>
        </div>
      </form>
      
      <div class="text-center mt-3">
        <p>Already have an account? <a href="login.php" class="text-decoration-none">Login here</a></p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - QuickStay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .login-option {
      transition: all 0.3s ease;
      cursor: pointer;
      border: 2px solid transparent;
    }
    .login-option:hover {
      border-color: #007bff;
      transform: translateY(-2px);
    }
    .login-option.active {
      border-color: #007bff;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body class="bg-light">

<?php include('inc/header.php'); ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <!-- Login Type Selection -->
      <div class="row mb-4">
        <div class="col-6">
          <div class="card login-option active" id="userOption" onclick="selectLoginType('user')">
            <div class="card-body text-center">
              <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
              <h5 class="mt-2">Login as User</h5>
              <p class="text-muted">Access your bookings and account</p>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card login-option" id="adminOption" onclick="selectLoginType('admin')">
            <div class="card-body text-center">
              <i class="bi bi-shield-lock" style="font-size: 2rem;"></i>
              <h5 class="mt-2">Login as Admin</h5>
              <p class="text-muted">Access admin dashboard</p>
            </div>
          </div>
        </div>
      </div>

      <!-- User Login Form -->
      <div class="card bg-white p-4 rounded shadow-sm" id="userLoginForm">
        <h4 class="mb-4 text-center fw-bold">User Login</h4>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">'.htmlspecialchars($_GET['error']).'</div>';
        }
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">'.htmlspecialchars($_GET['success']).'</div>';
        }
        ?>

        <form action="login_user.php" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control shadow-none" required>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control shadow-none" required>
          </div>
          <button type="submit" class="btn btn-dark w-100 shadow-none">Login</button>
        </form>
        
        <div class="text-center mt-3">
          <p>Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a></p>
        </div>
      </div>

      <!-- Admin Login Form -->
      <div class="card bg-white p-4 rounded shadow-sm" id="adminLoginForm" style="display: none;">
        <h4 class="mb-4 text-center fw-bold">Admin Login</h4>

        <form action="loginadmin.php" method="POST">
          <div class="mb-3">
            <label for="admin_email" class="form-label">Admin Email</label>
            <input type="email" name="admin_email" id="admin_email" class="form-control shadow-none" required>
          </div>
          <div class="mb-4">
            <label for="admin_pass" class="form-label">Password</label>
            <input type="password" name="admin_pass" id="admin_pass" class="form-control shadow-none" required>
          </div>
          <button type="submit" name="login" class="btn btn-success w-100 shadow-none">Login</button>
        </form>
        
        <div class="text-center mt-3">
          <p><a href="#" onclick="selectLoginType('user')" class="text-decoration-none text-muted">← Back to User Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function selectLoginType(type) {
    const userOption = document.getElementById('userOption');
    const adminOption = document.getElementById('adminOption');
    const userForm = document.getElementById('userLoginForm');
    const adminForm = document.getElementById('adminLoginForm');

    if (type === 'user') {
      userOption.classList.add('active');
      adminOption.classList.remove('active');
      userForm.style.display = 'block';
      adminForm.style.display = 'none';
    } else if (type === 'admin') {
      adminOption.classList.add('active');
      userOption.classList.remove('active');
      adminForm.style.display = 'block';
      userForm.style.display = 'none';
    }
  }
</script>
</body>
</html>

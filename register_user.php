<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $data = filteration($_POST);
    
    // Extract form data
    $name = trim($data['name']);
    $email = trim($data['email']);
    $phone = trim($data['phone']);
    $address = trim($data['address']);
    $pincode = trim($data['pincode']);
    $dob = trim($data['dob']);
    $password = $_POST['password']; // Don't filter password to preserve special characters
    $confirm_password = $_POST['confirm_password'];
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($address) || 
        empty($pincode) || empty($dob) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=" . urlencode("Please fill all required fields."));
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=" . urlencode("Please enter a valid email address."));
        exit();
    }
    
    // Validate password match
    if ($password !== $confirm_password) {
        header("Location: register.php?error=" . urlencode("Passwords do not match."));
        exit();
    }
    
    // Validate password strength
    if (strlen($password) < 6) {
        header("Location: register.php?error=" . urlencode("Password must be at least 6 characters long."));
        exit();
    }
    
    // Check if email already exists
    $check_email_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($con, $check_email_query);
    
    if (!$check_stmt) {
        header("Location: register.php?error=" . urlencode("Server error, please try again later."));
        exit();
    }
    
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: register.php?error=" . urlencode("Email address is already registered."));
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into database (without picture field)
    $insert_query = "INSERT INTO users (name, email, phone, address, pincode, dob, password, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $insert_stmt = mysqli_prepare($con, $insert_query);
    
    if (!$insert_stmt) {
        header("Location: register.php?error=" . urlencode("Server error, please try again later."));
        exit();
    }
    
    mysqli_stmt_bind_param($insert_stmt, "sssssss", 
        $name, $email, $phone, $address, $pincode, $dob, $hashed_password);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        // Redirect to login page with success message
        header("Location: login.php?success=" . urlencode("Registration successful! Please login with your credentials."));
        exit();
    } else {
        header("Location: register.php?error=" . urlencode("Registration failed, please try again."));
        exit();
    }
} else {
    // Not a POST request, redirect to registration page
    header("Location: register.php");
    exit();
}
?>

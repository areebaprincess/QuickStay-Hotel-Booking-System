<?php 
require('admin/inc/db_config.php'); 
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = trim($_POST['admin_email']); 
    $password = $_POST['admin_pass']; 

    if (empty($email) || empty($password)) { 
        header("Location: login.php?error=" . urlencode("Please enter both email and password.")); 
        exit(); 
    } 

    // Use prepared statements like user login
    $sql = "SELECT id, name, email, password FROM admin_login WHERE email = ?"; 
    $stmt = mysqli_prepare($con, $sql); 

    if (!$stmt) { 
        header("Location: login.php?error=" . urlencode("Server error, please try again later.")); 
        exit(); 
    } 

    mysqli_stmt_bind_param($stmt, "s", $email); 
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt); 

    // Two-step validation like user login
    if ($result && mysqli_num_rows($result) === 1) { 
        $admin = mysqli_fetch_assoc($result); 

        // For plain text passwords (recommended: use password_verify for hashed)
        if ($password === $admin['password']) { 
            $_SESSION['adminLogin'] = true; 
            $_SESSION['adminEmail'] = $admin['email']; 
            $_SESSION['adminName'] = $admin['name']; 
            $_SESSION['admin_id'] = $admin['id']; 

            header("Location: admin/allinfo.php"); 
            exit(); 
        } else { 
            header("Location: login.php?error=" . urlencode("Incorrect email or password.")); 
            exit(); 
        } 
    } else { 
        header("Location: login.php?error=" . urlencode("Incorrect email or password.")); 
        exit(); 
    } 
} else { 
    header("Location: login.php"); 
    exit(); 
} 
?>
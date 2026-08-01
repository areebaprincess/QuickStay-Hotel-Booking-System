<?php
require('admin/inc/db_config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Please enter both email and password."));
        exit();
    }

    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        header("Location: login.php?error=" . urlencode("Server error, please try again later."));
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user found and verify password
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            echo "<script>
                if(sessionStorage.getItem('redirectAfterLogin')) {
                    window.location.href = sessionStorage.getItem('redirectAfterLogin');
                    sessionStorage.removeItem('redirectAfterLogin');
                } else {
                    window.location.href = 'roomslogin.php';
                }
            </script>";
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

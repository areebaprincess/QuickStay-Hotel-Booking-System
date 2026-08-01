<?php
$host = "localhost";
$user = "root";
$pass = ""; // XAMPP's default MySQL password is empty
$dbname = "test"; // If you don't have a test DB, we will create it later

$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>

<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['available' => false, 'message' => 'Please login to check availability']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['available' => false, 'message' => 'Invalid request method']);
    exit;
}

$room_id = filteration($_POST['room_id']);
$check_in = filteration($_POST['check_in']);
$check_out = filteration($_POST['check_out']);
$adults = filteration($_POST['adults']);
$children = filteration($_POST['children']);

// Validate input
if(empty($room_id) || empty($check_in) || empty($check_out)) {
    echo json_encode(['available' => false, 'message' => 'Please fill all required fields']);
    exit;
}

// Validate dates
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$today = new DateTime();

if($check_in_date < $today) {
    echo json_encode(['available' => false, 'message' => 'Check-in date cannot be in the past']);
    exit;
}

if($check_out_date <= $check_in_date) {
    echo json_encode(['available' => false, 'message' => 'Check-out date must be after check-in date']);
    exit;
}

// Get room details
$room_query = "SELECT * FROM rooms WHERE id = ? LIMIT 1";
$room_stmt = mysqli_prepare($con, $room_query);
mysqli_stmt_bind_param($room_stmt, 'i', $room_id);
mysqli_stmt_execute($room_stmt);
$room_result = mysqli_stmt_get_result($room_stmt);

if(mysqli_num_rows($room_result) == 0) {
    echo json_encode(['available' => false, 'message' => 'Room not found']);
    exit;
}

$room = mysqli_fetch_assoc($room_result);

// Check guest capacity
if($adults > $room['adult'] || $children > $room['children']) {
    echo json_encode(['available' => false, 'message' => 'Guest capacity exceeded. This room allows maximum ' . $room['adult'] . ' adults and ' . $room['children'] . ' children']);
    exit;
}

// Check for overlapping bookings
$booking_query = "SELECT COUNT(*) as booked_rooms FROM bookings 
                 WHERE room_id = ? 
                 AND status IN ('confirmed', 'pending') 
                 AND (
                     (check_in <= ? AND check_out > ?) OR
                     (check_in < ? AND check_out >= ?) OR
                     (check_in >= ? AND check_out <= ?)
                 )";

$booking_stmt = mysqli_prepare($con, $booking_query);
mysqli_stmt_bind_param($booking_stmt, 'issssss', $room_id, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out);
mysqli_stmt_execute($booking_stmt);
$booking_result = mysqli_stmt_get_result($booking_stmt);
$booking_data = mysqli_fetch_assoc($booking_result);

$booked_rooms = $booking_data['booked_rooms'];
$available_rooms = $room['quantity'] - $booked_rooms;

if($available_rooms <= 0) {
    echo json_encode([
        'available' => false, 
        'message' => 'No rooms available for the selected dates. All ' . $room['quantity'] . ' rooms are already booked.'
    ]);
} else {
    echo json_encode([
        'available' => true, 
        'message' => $available_rooms . ' room(s) available for the selected dates',
        'available_rooms' => $available_rooms
    ]);
}

mysqli_stmt_close($room_stmt);
mysqli_stmt_close($booking_stmt);
?>
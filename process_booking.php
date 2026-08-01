<?php
// Set JSON header and disable error output to prevent HTML/warnings from breaking JSON
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');
// Remove this line: session_start(); - essentials.php already handles this

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to make a booking']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize input data
        $data = filteration($_POST);
        
        // Extract booking information
        $user_id = $_SESSION['user_id'];
        $room_id = $data['room_id'];
        $check_in = $data['check_in'];
        $check_out = $data['check_out'];
        $adults = $data['adults'];
        $children = $data['children'];
        $room_price = $data['room_price'];
        
        // Extract guest information
        $guest_name = $data['guest_name'];
        $guest_email = $data['guest_email'];
        $guest_phone = $data['guest_phone'];
        $id_card = $data['id_card'];
        $guest_address = $data['guest_address'];
        $guest_city = $data['guest_city'];
        $guest_pincode = $data['guest_pincode'] ?? '';
        $emergency_name = $data['emergency_name'] ?? '';
        $emergency_phone = $data['emergency_phone'] ?? '';
        $special_requests = $data['special_requests'] ?? '';
        
        // Calculate total amount
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $nights = $check_in_date->diff($check_out_date)->days;
        $total_amount = $nights * $room_price;
        
        // Generate booking reference
        $booking_ref = 'RC' . date('Ymd') . rand(1000, 9999);
        
        // Insert booking into database
        $insert_query = "INSERT INTO bookings (
            user_id, room_id, check_in, check_out, adults, children, 
            guest_name, guest_email, guest_phone, id_card, guest_address, 
            guest_city, guest_pincode, emergency_name, emergency_phone, 
            special_requests, total_amount, booking_ref, status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', NOW())";
        
        $insert_stmt = mysqli_prepare($con, $insert_query);
        
        if (!$insert_stmt) {
            echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . mysqli_error($con)]);
            exit;
        }
        
        mysqli_stmt_bind_param($insert_stmt, 'iissiisssssssssiss', 
            $user_id, $room_id, $check_in, $check_out, $adults, $children,
            $guest_name, $guest_email, $guest_phone, $id_card, $guest_address,
            $guest_city, $guest_pincode, $emergency_name, $emergency_phone,
            $special_requests, $total_amount, $booking_ref
        );
        
        if (mysqli_stmt_execute($insert_stmt)) {
            echo json_encode([
                'success' => true, 
                'message' => 'Booking confirmed successfully!',
                'booking_ref' => $booking_ref,
                'total_amount' => number_format($total_amount, 0),
                'nights' => $nights,
                'status' => 'confirmed'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database execution error: ' . mysqli_stmt_error($insert_stmt)]);
        }
        
        mysqli_stmt_close($insert_stmt);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'System error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
exit;
?>
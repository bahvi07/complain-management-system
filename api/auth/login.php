<?php
include '../../config/config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Validate phone input
if (!isset($_POST['phone']) || !preg_match('/^[6-9]\d{9}$/', $_POST['phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid phone number.']);
    exit;
}

$phone = $_POST['phone'];
$otp = rand(100000, 999999);
$is_used = 0;

$stmt = $conn->prepare("INSERT INTO otp_requests (phone, otp, is_used) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $phone, $otp, $is_used);

if ($stmt->execute()) {
    // In a real API, you would send SMS here
    // For now, we'll return the OTP in the response
    echo json_encode(['status' => 'success', 'otp' => $otp]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
}

$stmt->close();
$conn->close();
?>
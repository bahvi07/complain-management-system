<?php
include '../config/config.php'; // Make sure this file outputs nothing

header('Content-Type: application/json');

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
    echo json_encode(['status' => 'success', 'otp' => $otp]); // ✅ Clean JSON only
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
}

$stmt->close();
$conn->close();
?>

<?php
session_start(); // START SESSION HERE

require '../config/config.php';
date_default_timezone_set('Asia/Kolkata');

// Sanitize and get OTP
$otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
if (empty($otp) || !preg_match('/^\d{6}$/', $otp)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    exit;
}

// Get latest matching OTP (not used, not expired)
$stmt = $conn->prepare("
    SELECT * FROM otp_requests
    WHERE otp = ? AND is_used = 0 AND expires_at >= NOW()
    ORDER BY created_at DESC
    LIMIT 1
");
$stmt->bind_param("s", $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $otp_id = $row['id'];
    $phone = $row['phone'];

    // Mark OTP as used
    $update = $conn->prepare("UPDATE otp_requests SET is_used = 1 WHERE id = ?");
    $update->bind_param("i", $otp_id);
    $update->execute();

    // Mark user as logged in (optional: update in separate users table if you use one)
    $loginUpdate = $conn->prepare("UPDATE otp_requests SET is_logged_in = 1 WHERE phone = ?");
    $loginUpdate->bind_param("s", $phone);
    $loginUpdate->execute();

    // Set session variables
    $_SESSION['is_logged_in'] = true;
    $_SESSION['user_phone'] = $phone;

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'OTP is incorrect or expired']);
}
?>

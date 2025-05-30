<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if config file exists
if (!file_exists('../config/config.php')) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Config file not found']);
    exit;
}

include '../config/config.php';

header('Content-Type: application/json');

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Validate phone input
if (!isset($_POST['phone']) || !preg_match('/^[6-9]\d{9}$/', $_POST['phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid phone number.']);
    exit;
}

$phone = $_POST['phone'];
$otp = rand(100000, 999999);
$is_used = 0;

// Check if table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'otp_requests'");
if (!$tableCheck || $tableCheck->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Table otp_requests does not exist']);
    exit;
}

// Prepare statement
$stmt = $conn->prepare("INSERT INTO otp_requests (phone, otp, is_used) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssi", $phone, $otp, $is_used);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'otp' => $otp]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
include '../config/config.php';

// Get posted data
$id = $_POST['id'] ?? '';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? ''; // May be empty

if (!$id || !$name || !$email) {
    $_SESSION['admin_update_msg'] = ['type' => 'error', 'text' => 'Required fields are missing.'];
    header('Location: admin-edit.php');
    exit;
}

// Check if admin exists
$sql = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    $_SESSION['admin_update_msg'] = ['type' => 'error', 'text' => 'Admin not found.'];
    header('Location: admin-edit.php');
    exit;
}

// Update query
if (!empty($password)) {
    $update = $conn->prepare("UPDATE admin SET name = ?, email = ?, phone = ?, password = ? WHERE id = ?");
    $update->bind_param("ssssi", $name, $email, $phone, $password, $id); // Optional: use password_hash($password, PASSWORD_DEFAULT)
} else {
    $update = $conn->prepare("UPDATE admin SET name = ?, email = ?, phone = ? WHERE id = ?");
    $update->bind_param("sssi", $name, $email, $phone, $id);
}

if ($update->execute()) {
    $_SESSION['admin_update_msg'] = ['type' => 'success', 'text' => 'Admin details updated successfully.'];
} else {
    $_SESSION['admin_update_msg'] = ['type' => 'error', 'text' => 'Update failed. Please try again.'];
}

header('Location: admin-edit.php');
exit;

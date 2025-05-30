<?php
session_start();
ob_start(); // Buffer output to prevent accidental whitespace/errors
require '../config/config.php';
header('Content-Type: application/json');
error_reporting(0); 
$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Sanitize inputs
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $fname = isset($_POST['fName']) ? htmlspecialchars(trim($_POST['fName'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $category = isset($_POST['category']) ? htmlspecialchars(trim($_POST['category'])) : '';
    $complaint = isset($_POST['complaint']) ? htmlspecialchars(trim($_POST['complaint'])) : '';
    $location = isset($_POST['location']) ? htmlspecialchars(trim($_POST['location'])) : '';

    $errorMsg = [];
    $imagePath = null;

    // Validate inputs
    if (empty($name)) $errorMsg['name'] = "Name is required.";
    if (empty($fname)) $errorMsg['fName'] = "Father Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errorMsg['email'] = "Valid email is required.";
    if (empty($category)) $errorMsg['category'] = "Select a category.";
    if (empty($complaint)) $errorMsg['complaint'] = "Write the complaint.";
    if (empty($location)) $errorMsg['location'] = "Please select your location.";

    // Handle file upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/complain_upload/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new Exception("Could not create upload directory");
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($fileInfo, $_FILES['img']['tmp_name']);
        finfo_close($fileInfo);

        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mime, $allowed)) {
            $errorMsg['img'] = "Only JPG, PNG, and GIF images are allowed.";
        } elseif ($_FILES['img']['size'] > 2 * 1024 * 1024) {
            $errorMsg['img'] = "Image must be less than 2MB.";
        } else {
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('img_', true) . '.' . $ext;
            $dest = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['img']['tmp_name'], $dest)) {
                $imagePath = '../assets/images/complain_upload/' . $newFileName;
            } else {
                $errorMsg['img'] = "Failed to upload the image.";
            }
        }
    }

    if (!empty($errorMsg)) {
        $response['errors'] = $errorMsg;
        throw new Exception('Validation failed');
    }

    // Generate unique refId
    do {
        $refId = mt_rand(100000, 999999);
        $checkQuery = $conn->prepare("SELECT refid FROM complaints WHERE refid = ?");
        $checkQuery->bind_param("i", $refId);
        $checkQuery->execute();
        $checkQuery->store_result();
    } while ($checkQuery->num_rows > 0);
    $checkQuery->close();
 if (!isset($_SESSION['user_phone'])) {
    throw new Exception('User not authenticated');
}
$phone = $_SESSION['user_phone'];

   $stmt = $conn->prepare("INSERT INTO complaints (refid, name, father, email, phone, location, category, complaint, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssss", $refId, $name, $fname, $email, $phone, $location, $category, $complaint, $imagePath);

    
    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }

    $stmt->close();
    
    $response = [
        'success' => true,
        'message' => "Complaint submitted successfully! Your reference ID is: $refId",
        'refId' => $refId
    ];

} catch (Exception $e) {
    error_log($e->getMessage(), 3, 'logs/error.log'); // log to file
    $response['message'] = $e->getMessage();
}

// Ensure no output before this
echo json_encode($response);
exit;
?>
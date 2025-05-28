<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\vendor\autoload.php';
require '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['dept_email'] ?? '';
    $refid = $_POST['refid'] ?? '';
    $name = $_POST['name'] ?? 'Unknown';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $image = $_POST['image'] ?? '';


    if (empty($email) || empty($refid)) {
        echo json_encode(['success' => false, 'message' => 'Missing email or reference ID.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'help40617@gmail.com';
        $mail->Password   = 'lrmuluhlzrohwvoq';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('help40617@gmail.com', 'Complaint Management System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        if (!empty($image) && file_exists($image)) {
            $mail->addAttachment($image); // attaches the file
        }

        $mail->Subject = "Complaint Forwarded (Ref ID: $refid)";

        $mail->Body = "
    <h3>Complaint Forwarded</h3>
    <p><strong>Reference ID:</strong> $refid</p>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Location:</strong> $location</p>
    <p><strong>Image:</strong><br>" .
            (!empty($image) ? "<img src='$image' alt='Complaint Image' style='max-width:100%;height:auto;'/>" : "No image provided.") . "</p>
    <p>This complaint has been forwarded to your department for review and action.</p>
";


        $mail->send();
        // echo json_encode(['success' => true, 'message' => 'Email forwarded successfully.']);.
          updateStatus($refid,$conn);  
       echo " <script>
        alert('Complain Forwarded successfully');
        window.location.href='../admin/complaints.php';
        </script>";
        } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Email failed to send.']);
    }
}

function updateStatus($ref, $conn) {
    $stmt = $conn->prepare("UPDATE complaints SET status='forward' WHERE refid=?");
    $stmt->bind_param('s', $ref);
    if ($stmt->execute()) {
        return true;
    } else {
        // Log error if needed
        return false;
    }
}
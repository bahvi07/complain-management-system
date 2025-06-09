<?php
// Update these paths:
require_once '../phpmailer/src/Exception.php';
require_once '../phpmailer/src/PHPMailer.php';
require_once '../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['dept_email'] ?? '';
    $refid = $_POST['refid'] ?? '';
    $name = $_POST['name'] ?? 'Unknown';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $image = $_POST['image'] ?? '';

    if (empty($email) || empty($refid)) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Missing Information!',
                text: 'Email or Reference ID is missing!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../admin/complaints.php';
            });
        </script>
        </body>
        </html>";
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

        // FIXED: Correct path from mail_api folder to assets folder
        if (!empty($image)) {
            // Since script is in mail_api folder, we need to go up one level to reach cms root
            $imagePath = dirname(__DIR__) . '/assets/images/complain_upload/' . $image;
            
            // Alternative absolute path method
            $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/cms/assets/images/complain_upload/' . $image;
            
            // Debug logging
            error_log("Script location: " . __FILE__);
            error_log("Trying relative path: " . $imagePath);
            error_log("Trying absolute path: " . $absolutePath);
            
            if (file_exists($imagePath)) {
                $mail->addAttachment($imagePath, $image);
                error_log("SUCCESS: File attached using relative path");
            } elseif (file_exists($absolutePath)) {
                $mail->addAttachment($absolutePath, $image);
                error_log("SUCCESS: File attached using absolute path");
            } else {
                error_log("ERROR: File not found at either path");
                error_log("Image filename: " . $image);
                
                // List files in the directory for debugging
                $uploadDir = dirname(__DIR__) . '/assets/images/complain_upload/';
                if (is_dir($uploadDir)) {
                    $files = scandir($uploadDir);
                    error_log("Files in upload directory: " . implode(', ', $files));
                } else {
                    error_log("Upload directory does not exist: " . $uploadDir);
                }
            }
        }

        $mail->isHTML(true);
        $mail->Subject = "Complaint Forwarded (Ref ID: $refid)";

        $mail->Body = "
        <h3>Complaint Forwarded</h3>
        <p><strong>Reference ID:</strong> $refid</p>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Location:</strong> $location</p>
        <p>This complaint has been forwarded to your department for review and action.</p>
        ";

        $mail->send();
        
        // Update status
        updateStatus($refid, $conn);
        
        // Success with SweetAlert
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Complaint forwarded successfully!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = '../admin/complaints.php';
            });
        </script>
        </body>
        </html>";
        
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        
        // Error with SweetAlert, show detailed error
        $errorMsg = addslashes($mail->ErrorInfo ?: $e->getMessage());
        echo "<!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Email Failed!',
                html: 'Failed to send email.<br><b>Reason:</b> " . $errorMsg . "',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../admin/complaints.php';
            });
        </script>
        </body>
        </html>";
    }
}

function updateStatus($ref, $conn) {
    $stmt = $conn->prepare("UPDATE complaints SET status='forward' WHERE refid=?");
    $stmt->bind_param('s', $ref);
    return $stmt->execute();
}
?>
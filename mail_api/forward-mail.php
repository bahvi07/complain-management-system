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
        
        // Error with SweetAlert
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
                text: 'Failed to send email. Please try again.',
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
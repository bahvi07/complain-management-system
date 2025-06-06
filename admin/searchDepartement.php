<?php
require '../config/config.php';
include '../auth/admin-auth-check.php';

$area = $_POST['area'] ?? '';
$category = $_POST['category'] ?? '';
$refid = $_POST['refid'] ?? '';

$stmt = $conn->prepare("SELECT * FROM departments WHERE area LIKE ? AND category = ?");
$likeArea = "%$area%";
$stmt->bind_param("ss", $likeArea, $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div class='card p-2 mb-2'>
            <strong>{$row['name']}</strong> ({$row['email']})<br>
            <small><b>Area:</b> {$row['area']}</small><br>
          <form method='POST' action='../mail_api/forward-mail.php' class='d-inline forwardForm'>
  <input type='hidden' name='refid' value='" . htmlspecialchars($refid) . "'>
  <input type='hidden' name='dept_email' value='" . htmlspecialchars($row['email']) . "'>
  <input type='hidden' name='name' value='" . htmlspecialchars($_POST['name'] ?? '') . "'>
  <input type='hidden' name='phone' value='" . htmlspecialchars($_POST['phone'] ?? '') . "'>
  <input type='hidden' name='location' value='" . htmlspecialchars($_POST['location'] ?? '') . "'>
  <input type='hidden' name='image' value='" . htmlspecialchars($_POST['image'] ?? '') . "'>

  <button type='submit' class='btn btn-sm btn-success mt-2'>Forward</button>
</form>

          </div>";
  }
} else {
  echo "<div class='text-muted'>No matching department found.</div>";
}
?>

<?php
session_start();
include '../includes/admin-header.php';
include '../config/config.php';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id = isset($_POST['adminId']) ? trim($_POST['adminId']) : '';
    $pswd = isset($_POST['password']) ? trim($_POST['password']) : '';

    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // Verify password using password_verify
       if (password_verify($pswd, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['last_activity'] = time(); // add this
    header("Location:../admin/admin-dashboard.php");
    exit();
}
else {
            $errorMsg = "Wrong admin ID or password.";
        }
    } else {
        $errorMsg = "Wrong admin ID or password.";
    }
}
?>
<style>
  .custom-body{
      background: linear-gradient(to bottom right, #fde4dc, #f8cfc3);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      background: #fff6f2;
      border-radius: 20px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
      overflow: hidden;
      display: flex;
      flex-direction: row;
      max-width: 900px;
      width: 100%;
    }
    .image-section {
      flex: 1;
      background-color: #fff6f2;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }
    .image-section img {
      max-width: 100%;
      height: auto;
      
    }
    .form-section {
      flex: 1;
      background-color: #ffe5da;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .form-section h2 {
      margin-bottom: 30px;
      color:#FF4500;
    }
    .form-section .btn {
      background-color: #f15a29;
      border: none;
    }
    .form-section .btn:hover {
      background-color: #e14b20;
    }
</style>
</head>

<body class="custom-body">

  <div class="login-container">
    <!-- Image section -->
    <div class="image-section">
      <img src="../assets/images/general_images/loginImg.png" alt="Login Illustration" />
    </div>

    <div class="form-section">
      <h2>Login</h2>

      <form method="post" action="">
        <div class="mb-3">
          <label for="adminId" class="form-label">Admin Id</label>
          <input type="text" class="form-control" id="adminId" name="adminId" maxlength="50" placeholder="complainAdmin123" required />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Sign in</button>
      </form>
    </div>
  </div>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($errorMsg)) : ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?= addslashes($errorMsg) ?>'
      });
    </script>
  <?php endif; ?>


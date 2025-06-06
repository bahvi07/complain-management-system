<?php
include '../includes/admin-header.php';
include '../config/config.php';
include '../includes/admin-nav.php';
include '../auth/admin-auth-check.php';
?>
</head>

<body>
<div class="top-head bg-light">
    <div class="brand">
        <img src="../assets/images/general_images/Bjplogo.jpg" alt="Logo">
        Edit Admin Details
    </div>
    <div class="">
        Admin, <?= $_SESSION['admin_name'] ?? 'Admin' ?>
    </div>
</div>

<div class="complaint-center row p-2">
    <div class="card shadow p-4">
        <h4 class="mb-3">Admin Details</h4>
        <?php
        $admin_id = $_SESSION['admin_id'] ?? '';
        $sql = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0):
            $admin = $result->fetch_assoc();
        ?>
            <form action="update-admin.php" method="POST">
                <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($admin['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($admin['phone'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-warning">Update Details</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Admin details not found.</div>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin_update_msg'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: '<?= $_SESSION['admin_update_msg']['type'] ?>',
    title: '<?= $_SESSION['admin_update_msg']['type'] === 'success' ? 'Success' : 'Error' ?>',
    text: '<?= $_SESSION['admin_update_msg']['text'] ?>',
});
</script>
<?php unset($_SESSION['admin_update_msg']); endif; ?>

    </div>
</div>

<?php include '../includes/admin-footer.php'; ?>

<?php include '../includes/header.php';
include '../config/config.php';
include '../auth/auth-check.php';
?>
</head>

<body>

  <!-- Top Header Bar -->
  <div class="top-header">
    <div class="brand">
      <img src="../assets/images/general_images/Bjplogo.jpg" alt="Logo">
      Vidhayak Seva Kendra
    </div>
    <button class="sidebar-toggle" id="toggleSidebar">☰</button>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
      <li><a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#complaintModal"><i class="fas fa-plus-circle"></i> Create Complaint</a></li>
      <li><a class="nav-link" href="./myComplaints.php"><i class="fas fa-list-alt"></i> My Complaints</a></li>
      <li><a class="nav-link" href="#" data-bs-target="#checkStatus" data-bs-toggle="modal"><i class="fas fa-tasks"></i> Check Status</a></li>
      <li><a class="nav-link" href="../delete_ac.php"><i class="fas fa-trash-alt"></i> Delete Account</a></li>
      <li><a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="content">
      <div class="row gx-3 gy-3" >
        <div class="col-12">
          <div class="dashboard-card" id="create" data-bs-toggle="modal" data-bs-target="#complaintModal">
            <i class="fas fa-plus-circle"></i>
            <span>Create Complaint</span>
          </div>
        </div>

        <div class="col-6 col-md-6">
          <div class="dashboard-card" id="show">
            <i class="fas fa-list-alt"></i>
            <span>My Complaints</span>
          </div>
        </div>

        <div class="col-6 col-md-6">
          <div class="dashboard-card" id="status" data-bs-toggle="modal" data-bs-target="#checkStatus">
            <i class="fas fa-tasks"></i>
            <span>Check Status</span>
          </div>
        </div>


        <div class="col-6 col-md-6">
          <div class="dashboard-card" id="office">
            <i class="fas fa-building"></i>
            <span>Our Office</span>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="dashboard-card" id="logout">
            <i class="fas fa-reply"></i>
            <span>Logout</span>
          </div>
        </div>

        <div class="col-12">
          <div class="dashboard-card" id="delete" data-bs-toggle="modal" data-bs-target="#deleteAcModal">
            <i class="fas fa-trash-alt"></i>
            <span>Delete Account</span>
          </div>
        </div>

      </div>
  </div>


  <!-- Complaint Form Modal -->
  <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="complaintModalLabel">Enter Complaint Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" id="complaintForm">

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="col-md-6">
                <label for="fatherName" class="form-label">Father's Name:</label>
                <input type="text" class="form-control" id="fatherName" name="fName" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Location:</label>
                <textarea class="form-control location-display" id="locationDisplay" name="location" rows="3" placeholder="Enter Location"></textarea>
              </div>
              <div class="col-md-6">
                <label for="img" class="form-label">Upload Photo:</label>
                <input type="file" class="form-control" id="img" name="img" accept="image/*">
              </div>
            </div>

            <div class="mb-3 ">
              <label for="complain" class="form-label">Complaint Category:</label>
              <select class="form-select text-center" id="complain" name="category" required>
                <option value="">-- Select Option --</option>
                <option value="Water">Water Related</option>
                <option value="Electricity">Electricity Related</option>
                <option value="Land">Land Related</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="complaint" class="form-label">Describe Your Complaint:</label>
              <textarea class="form-control" id="complaint" name="complaint" rows="4" required></textarea>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" id="submitComplain" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Check Status Modal -->
  <div class="modal fade" id="checkStatus" tabindex="-1" aria-labelledby="checkStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 p-0">
        <div class="modal-header rounded-top-4" style="background:#F15922;" >
          <h5 class="modal-title text-white" id="checkStatusLabel" >
            <i class="fas fa-search me-2"></i>Check Complaint Status
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="./viewStatus.php" id="viewStatus" method="post">
          <div class="modal-body">
            <div class="mb-3">
              <label for="ref" class="form-label"><strong>Reference ID</strong></label>
              <input type="text" class="form-control" id="ref" name="ref" placeholder="Enter your Ref ID" required>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" data-bs-target="#checkStatus" data-bs-toggle="modal" class="btn w-100 text-white" id="chk"  style="background:#F15922;">Check Status</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Account Modal -->
<div class="modal fade" id="deleteAcModal" tabindex="-1" aria-labelledby="deleteAcModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      
      <!-- Modal Header -->
      <div class="modal-header rounded-top-4" style="background:#F15922;">
        <h5 class="modal-title text-white" id="deleteAcModalLabel">
          <i class="fas fa-user-times me-2"></i>Delete Account
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Form -->
      <form action="" method="POST" id="deleteForm">
        <div class="modal-body">
          <p class="text-danger fw-semibold">Are you sure you want to permanently delete your account?</p>
          <input type="hidden" name="phone" value="<?= $_SESSION['user_phone'] ?? '' ?>">
        </div>

        <div class="modal-footer d-flex flex-column gap-2">
          <button type="button" id="delete" class="btn btn-danger w-100 rounded-pill">
            <i class="fas fa-trash-alt me-2"></i>Yes, Delete My Account
          </button>
          <button type="button" class="btn btn-secondary w-100 rounded-pill" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

  <?php include '../includes/footer.php'; ?>

  <script>
    document.getElementById('show').addEventListener('click', () => {
      window.location.href = "./myComplaints.php";
    });


    document.getElementById('logout').addEventListener('click',()=>{
window.location.href="../auth/logout.php";
    });
    
    // Delete Account Script

const delBtn = document.getElementById('delete');

delBtn.addEventListener('click', async (e) => {
  e.preventDefault();

  const form = document.getElementById("deleteForm");
  const formData = new FormData(form);

  // Disable button while processing
  delBtn.disabled = true;
  delBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Deleting...`;

  try {
   const response = await fetch('./delete-account.php', {
  method: 'POST',
  body: formData,
});

const text = await response.text();

try {
  const result = JSON.parse(text);

  if (result.success) {
    Swal.fire({
      icon: 'success',
      title: 'Deleted',
      text: result.message,
    }).then(() => {
      window.location.href = '../index.php';
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: result.message || 'Unable to delete your account.',
    });
  }
} catch (err) {
  console.error("Invalid JSON:", text);
  Swal.fire({
    icon: 'error',
    title: 'Server Error',
    text: 'Unexpected server response. Check console for details.',
  });
}

  } finally {
    delBtn.disabled = false;
    delBtn.innerHTML = 'Delete My Account';
  }
});

  </script>
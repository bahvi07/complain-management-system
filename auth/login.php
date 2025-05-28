<?php 
 session_start();
 include '../includes/header.php'; 
 if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
     header("Location:../user/user-dashboard.php");
     exit;
 }

?>
</head>
<body class="custom-body">
  

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card login-card text-center">

    <img src="../assets/images/general_images/Bjplogo.jpg" class="img-fluid rounded-circle mx-auto login-logo" alt="Logo">

    <p class="" style="font-weight: 700;
  color: #FF4500; font-size:1.5rem;">
      Vidhayak Sewa Kendra
    </p>

    <div class="form-wrapper bg-light">
      <h3 class="form-heading" style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #333;">
        Login with Phone
      </h3>

      <form id="otpForm">
        <div class="mb-3 text-start">
          <label for="phone" class="form-label fw-semibold mt-2" style="font-family: 'Poppins', sans-serif;">Enter Phone Number</label>
          <input type="tel" id="phone" name="phone"
            class="form-control border-0 border-bottom border-black rounded-0 bg-transparent mb-5"
            placeholder="+91 9900000000"
            maxlength="10"
            pattern="^[6-9]\d{9}$"
            title="Please enter a valid 10-digit phone number starting with 6-9"
            required>
        </div>

        <button type="button" class="btn w-100 login-btn otpBtn" id="otp">Send OTP</button>
      </form>
    </div>
  </div>
</div>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
    <div class="modal-content p-4" style="border-radius: 20px; background: #fff5f2; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);">
      <div class="modal-header border-0">
        <h5 class="modal-title text-center w-100" id="otpModalLabel" style="font-family: 'Poppins', sans-serif; font-weight: bold; color: #FF4500;">
          Verify Your OTP
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="verifyOtp">
          <div class="d-flex justify-content-center gap-2 mb-4">
            <?php for ($i = 0; $i < 6; $i++): ?>
              <input type="text" maxlength="1" name="otp[]" inputmode="numeric" pattern="[0-9]*"
                class="otp-input text-center form-control border-0 border-bottom border-black rounded-0 bg-transparent"
                required>
            <?php endfor; ?>
          </div>
          <button type="button" class="btn w-100" id="verifyOtpBtn"
            style="background: linear-gradient(to right, #FF7E5F, #FD3A69); color: white; font-weight: 600; border-radius: 50px;">
            Verify OTP
          </button>
          <p class="text-center mt-2">Resend</p>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<!--Custom Js-->
<script src="../assets/js/otp.js"></script>
</body>
</html>
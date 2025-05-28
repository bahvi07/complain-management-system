document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggleSidebar");
  const sidebar = document.getElementById("sidebar");

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("show");
    });
  }

// Send Complain through AJAX
const submitBtn = document.getElementById("submitComplain");
  if (submitBtn) {
    submitBtn.addEventListener("click", async (e) => {
    e.preventDefault();

    const form = document.getElementById("complaintForm");
    const submitBtn = document.getElementById("submitComplain");
    const formData = new FormData(form);

    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Submitting...';

    try {
      const response = await fetch("raise-complaint.php", {
        method: "POST",
        body: formData,
      });

      const rawText = await response.text(); // Read once
      console.log("Raw response:", rawText);

      let result = {};
      try {
        result = JSON.parse(rawText);
        console.log("Parsed JSON:", result);
      } catch (parseError) {
        throw new Error("Invalid JSON response from server");
      }

      if (result.success) {
        Swal.fire({
          icon: "success",
          title: "Success!",
          html:
            result.message +
            (result.refId
              ? `<br><strong>Reference ID:</strong> ${result.refId}`
              : ""),
          confirmButtonColor: "#3085d6",
        }).then(() => {
          form.reset();
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("complaintModal")
          );
          modal.hide();
        });
      } else {
        let errorMessage = result.message || "Something went wrong";
        if (result.errors) {
          errorMessage += "<br>" + Object.values(result.errors).join("<br>");
        }

        Swal.fire({
          icon: "error",
          title: "Error",
          html: errorMessage,
          confirmButtonColor: "#d33",
        });
      }
    } catch (error) {
      console.error("Caught Error:", error);
      Swal.fire({
        icon: "error",
        title: "Request Failed",
        html: error.message || "Unknown error occurred",
        confirmButtonColor: "#d33",
      });
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = "Submit";
    }
 });
  }
});


  // My complaints


 
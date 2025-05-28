 $(document).ready(function() {
            $('#complaintTable').DataTable();
        });


        $(document).ready(function() {
            $(document).on('click', '.viewImageBtn', function() {
                const imagePath = $(this).data('image') || 'placeholder.jpg';
                $('#complaintImage').attr('src', imagePath);
            });
        });

        // forward complaint script
        $(document).on('click', '.forwardBtn', function() {
            const category = $(this).data('category');
            const refid = $(this).data('refid');
            const name = $(this).data('name');
            const phone = $(this).data('phone');
            const location = $(this).data('location');
            const image = $(this).data('img');
            $('#forward_name').val(name);
            $('#forward_image').val(image);

            $('#forward_location').val(location);
            $('#forward_phone').val(phone);
            $('#forward_category').val(category);
            $('#forward_refid').val(refid);
            $('#search_area').val('');
            $('#departmentList').html('');
        });

        // Handle area search
        $('#search_area').on('input', function() {
            const area = $(this).val().trim();
            const category = $('#forward_category').val();
            const refid = $('#forward_refid').val();
            const name = $('#forward_name').val();
            const phone = $('#forward_phone').val();
            const location = $('#forward_location').val();
            const image = $('#forward_image').val();
            if (area.length < 2) {
                $('#departmentList').html('');
                return;
            }

            $.ajax({
                url: './searchDepartement.php',
                method: 'POST',
                data: {
                    area,
                    category,
                    refid,
                    name,
                    phone,
                    location,
                    image
                },
                success: function(data) {
                    $('#departmentList').html(data);
                }
            });
        });

        // cahnge table view
        document.addEventListener('DOMContentLoaded', () => {
            const tabMap = {
                newComp: 'pending',
                rejComp: 'rejected',
                forwardComp: 'forwarded'
            };

        Object.keys(tabMap).forEach(tabId => {
    const tabElement = document.getElementById(tabId);
    if (tabElement) {
        tabElement.addEventListener('click', (e) => {
            e.preventDefault();

            // Hide all tbody sections
            Object.values(tabMap).forEach(tbodyId => {
                document.getElementById(tbodyId).classList.add('d-none');
            });

            // Remove 'active' class from all tabs
            document.querySelectorAll('#complaintTabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Show the selected tbody
            document.getElementById(tabMap[tabId]).classList.remove('d-none');

            // Mark tab active
            e.target.classList.add('active');
        });
    }
});
        });

    
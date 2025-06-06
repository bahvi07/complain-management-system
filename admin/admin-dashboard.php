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
            Admin Dashboard
        </div>
        <div class="">
            Welcome, <?= $_SESSION['admin_name'] ?? 'Admin' ?>
        </div>
    </div>
    <div class="dashboard-content row">
        <div class="col main-content">
            <div class="card-container d-flex flex-wrap gap-4 p-4 justify-content-center">
                <!-- New Complaints -->
                <div class="status-card bg-primary text-white">
                    <i class="fas fa-envelope-open-text fa-2x mb-2"></i>
                    <h5>New Complaints</h5>
                    <span class="count">
                        <?php
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        $sql = "SELECT COUNT(*) AS total FROM complaints WHERE status='pending'";
                        $result = mysqli_query($conn, $sql);
                        $pendingCount = ($result && $row = mysqli_fetch_assoc($result)) ? $row['total'] : 0;
                        echo $pendingCount;
                        ?>
                    </span>
                </div>

                <!-- Pending Complaints -->
                <div class="status-card bg-secondary text-white">
                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                    <h5>Pending</h5>
                    <span class="count"> <?php
                                            $currentPage = basename($_SERVER['PHP_SELF']);
                                            $sql = "SELECT COUNT(*) AS total FROM complaints WHERE status='pending'";
                                            $result = mysqli_query($conn, $sql);
                                            $pendingCount = ($result && $row = mysqli_fetch_assoc($result)) ? $row['total'] : 0;
                                            echo $pendingCount;
                                            ?></span>
                </div>

                <!-- Resolved Complaints -->
                <div class="status-card bg-success text-white">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h5>Resolved</h5>
                    <span class="count"> <?php
                                            $currentPage = basename($_SERVER['PHP_SELF']);
                                            $sql = "SELECT COUNT(*) AS total FROM complaints WHERE status='resolve'";
                                            $result = mysqli_query($conn, $sql);
                                            $resCount = ($result && $row = mysqli_fetch_assoc($result)) ? $row['total'] : 0;
                                            echo $resCount;
                                            ?></span>
                </div>

                <!-- Rejected Complaints -->
                <div class="status-card bg-danger text-white">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h5>Rejected</h5>
                    <span class="count">
                        <?php
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        $sql = "SELECT COUNT(*) AS total FROM complaints WHERE status='reject'";
                        $result = mysqli_query($conn, $sql);
                        $rejectCount = ($result && $row = mysqli_fetch_assoc($result)) ? $row['total'] : 0;
                        echo $rejectCount;
                        ?>
                    </span>
                </div>

                <!-- Forwarded Complaints -->
                <div class="status-card bg-warning text-dark">
                    <i class="fas fa-share-square fa-2x mb-2"></i>
                    <h5>Forwarded</h5>
                    <span class="count">
                        <?php
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        $sql = "SELECT COUNT(*) AS total FROM complaints WHERE status='forward'";
                        $result = mysqli_query($conn, $sql);
                        $forwardCount = ($result && $row = mysqli_fetch_assoc($result)) ? $row['total'] : 0;
                        echo $forwardCount;
                        ?>
                    </span>
                </div>
            </div>
            <div class="row" style="margin-left: 25px;">
                <!-- Chart Container -->
                <div class="col-7 bg-light m-2" style="border-radius: 20px;">
                    <canvas id="resolvedChart" width="600" height="300"></canvas>
                </div>
                <!-- Chart Container -->
                <div class="col-4 bg-light m-2" style="border-radius: 20px;">
                    <canvas id="complaintByArea" width="400" height="400"></canvas>
                </div>
                <div class="col-7 bg-light m-2" style="border-radius: 20px;" id="recentTable">
                    <h4 class="pt-2 mb-3">Recently Resolved Complaints</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="recentResolvedTable">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Ref ID</th>
                                    <th>User Name</th>
                                    <th>Category</th>
                                    <th>Resolved Date</th>
                                    <th>City</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM complaints WHERE status = ? AND updated_at >= NOW() - INTERVAL 7 DAY");
                                $status = 'resolve';
                                $stmt->bind_param("s", $status);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    $refId = htmlspecialchars($row['refid']);
                                    $name = htmlspecialchars($row['name'] ?? 'Unknown');
                                    $category = htmlspecialchars($row['category'] ?? '');
                                    $resolvedAt = htmlspecialchars($row['updated_at'] ?? '');
                                    $location = htmlspecialchars($row['location'] ?? '');

                                    echo "<tr>
        <td>$refId</td>
        <td>$name</td>
        <td>$category</td>
               <td>$resolvedAt</td>
        <td>$location</td>
        </tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-4 bg-light m-2" style="border-radius: 20px;">
                    <canvas id="resolvedUnresolvedChart" width="600" height="350"></canvas>
                </div>

            </div>
            <?php include '../includes/admin-footer.php'; ?>
            <script>
                // Utility to generate a random color in rgba format
                function getRandomColor(opacity = 0.7) {
                    const r = Math.floor(Math.random() * 200);
                    const g = Math.floor(Math.random() * 200);
                    const b = Math.floor(Math.random() * 200);
                    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
                }

                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                const resolvedCounts = months.map(() => Math.floor(Math.random() * 50 + 10));
                const barColors = months.map(() => getRandomColor(0.7));
                const borderColors = barColors.map(color => color.replace('0.7', '1'));

                const ctx = document.getElementById('resolvedChart').getContext('2d');
                const resolvedChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Resolved Complaints',
                            data: resolvedCounts,
                            backgroundColor: barColors,
                            borderColor: borderColors,
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Resolved Complaints'
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Monthly Resolved Complaints (Sample Data)',
                                font: {
                                    size: 18
                                }
                            },
                            legend: {
                                display: false
                            }
                        }
                    }
                });

           fetch('./reports/get-city-data.php')
    .then(response => response.json())
    .then(chartData => {
        const cityLabels = chartData.labels;
        const complaintsData = chartData.data;

        const colorPalette = [
            '#FF6384', '#36A2EB', '#FFCE56',
            '#4BC0C0', '#9966FF', '#FF9F40'
        ];

        const ctxCity = document.getElementById('complaintByArea').getContext('2d');
        const complaintByArea = new Chart(ctxCity, {
            type: 'pie',
            data: {
                labels: cityLabels,
                datasets: [{
                    data: complaintsData,
                    backgroundColor: colorPalette.slice(0, cityLabels.length), // match colors
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Higher Severity Area',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error loading chart data:', error);
    });

           fetch('./reports/get-resolveVsreject.php')
    .then(response => response.json())
    .then(chartData => {
        const ctxRU = document.getElementById('resolvedUnresolvedChart').getContext('2d');

        const resolvedUnresolvedChart = new Chart(ctxRU, {
            type: 'pie',
            data: {
                labels: chartData.labels, // ['resolve', 'reject', 'pending']
                datasets: [{
                    data: chartData.data,   // [count, count, count]
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',   // green for resolve
                        'rgba(220, 53, 69, 0.7)',   // red for reject
                        'rgba(255, 193, 7, 0.7)'    // yellow for pending
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Resolved vs Rejected vs Pending Complaints',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error fetching resolved vs rejected chart data:', error);
    });

            </script>
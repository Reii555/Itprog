<!DOCTYPE html>

<html>
    <meta charset="UTF-8">
    <title>Server Dashboard</title>
    <link rel="stylesheet" href="Server_Dashboard_Design.css">
<body>
    <div class="header">
        <img src="../icons/CSP_logo.png" alt="CSP Logo" class="CSP-logo">
        <h3>Centralized Scholarship Portal / Admin Navigation</h3>
    </div>

    <br>

    <section class="dashboard-n-settings">
    <h1>Dashboard</h1>

    <div class="settings-dropdown">
    <button class="settings-btn">Settings ▼</button>
    <div class="dropdown-menu">
        <a href="#" class="dropdown-item-logout" onclick="return confirmLogout()">Logout</a>
        <a href="IT_Support.php" class="dropdown-item">Call for IT Support</a>
        </div>
    </div>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php"; 
                return true;
            }
            return false;
        }
    </script>

    </section>

    <section class="dashboard-stats-holder">
        <section class="dashboard-stats">
            <section class="card-total-scholarships">
                <h4>Total Scholarships</h4>    
                    <section class="stat-card">
                        <h4>(insert value)</h4>
                    </section>
            </section>

            <section class="card-active">
                <h4>Active</h4>    
                    <section class="stat-card">
                        <h4>(insert value)</h4>      
                     </section>
            </section>

            <section class="card-total-applicant">
                <h4>Total Applicants</h4>
                    <section class="stat-card">
                        <h4>(insert value)</h4>
                    </section>
            </section>

            <section class="card-pending">
                <h4>Pending</h4>
                    <section class="stat-card">
                        <h4>(insert value)</h4>
                    </section>
            </section>
        </section>
    </section>

<section class="action-cards-grid">
    <div class="action-row">
        <a href="scholarship-mgmt.php" class="action-card" style="text-decoration: none;">
            <div class="card-content">
                <div class="icon-wrapper">
                    <img src="../icons/temp_image.png" alt="Manage Scholarships" class="card-icon">
                </div>
                <h3>Manage Scholarships</h3>
            </div>
        </a>

        <a href="AppMgmt.php" class="action-card" style="text-decoration: none;">
            <div class="card-content">
                <div class="icon-wrapper">
                    <img src="../icons/temp_image.png" alt="Review Applications" class="card-icon">
                </div>
                <h3>Review Applications</h3>
            </div>
        </a>
    </div>

    <div class="action-row single">
        <a href="statistics_reports.php" class="action-card" style="text-decoration: none;">
            <div class="card-content">
                <div class="icon-wrapper">
                    <img src="../icons/temp_image.png" alt="Statistics & Reports" class="card-icon">
                </div>
                <h3>Statistics & Reports</h3>
            </div>
        </a>
    </div>
</section>

</body>
    </html>
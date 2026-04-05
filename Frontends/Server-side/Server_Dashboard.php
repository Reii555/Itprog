<!-- 

SERVER DASHBOARD PAGE

This file serves as the main dashboard for administrators after logging
in. It displays key statistics about scholarships and applications, such 
as total scholarships, active scholarships, total applicants, and pending 
applications. The dashboard also provides navigation options for managing 
scholarships, reviewing applications, and accessing statistics and reports. 
Additionally, it includes a settings dropdown for logging out and calling 
IT support.

-->

<?php
session_start();
include("../../db_connect.php");

//check login
if(!isset($_SESSION['account_id'])){
    header("Location: ./login.php");
    exit();
}

//get admin ID
$admin_id = null;
$admin_query = mysqli_query($conn, "SELECT admin_id FROM administrators WHERE account_id = '" .
                mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'");
if($admin_query && mysqli_num_rows($admin_query) > 0){
    $admin_data = mysqli_fetch_assoc($admin_query);
    $admin_id = $admin_data['admin_id'];
}

// Get statistics from database
// Total Scholarships
$total_scholarships_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM scholarships");
$total_scholarships = mysqli_fetch_assoc($total_scholarships_query)['total'];

// Active Scholarships (Ongoing and Published)
$active_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM scholarships WHERE status = 'Ongoing' AND release_status = 'Published'");
$active_scholarships = mysqli_fetch_assoc($active_query)['total'];

// Total Applicants (unique students who applied)
$total_applicants_query = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as total FROM applications");
$total_applicants = mysqli_fetch_assoc($total_applicants_query)['total'];

// Pending Applications
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM applications WHERE status = 'Submitted'");
$pending_applications = mysqli_fetch_assoc($pending_query)['total'];
?>

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
        <a href="logout.php" class="dropdown-item-logout" onclick="return confirmLogout()">Logout</a>
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
                        <h4><?php echo $total_scholarships; ?></h4>
                    </section>
            </section>

            <section class="card-active">
                <h4>Active</h4>    
                    <section class="stat-card">
                        <h4><?php echo $active_scholarships; ?></h4>      
                     </section>
            </section>

            <section class="card-total-applicant">
                <h4>Total Applicants</h4>
                    <section class="stat-card">
                        <h4><?php echo $total_applicants; ?></h4>
                    </section>
            </section>

            <section class="card-pending">
                <h4>Pending</h4>
                    <section class="stat-card">
                        <h4><?php echo $pending_applications; ?></h4>
                    </section>
            </section>
        </section>
    </section>

<section class="action-cards-grid">
    <div class="action-row">
        <a href="scholarship-mgmt.php" class="action-card" style="text-decoration: none;">
            <div class="card-content">
                <div class="icon-wrapper">
                    <img src="../icons/MANAGE SCHOLARSHIPS.png" alt="Manage Scholarships" class="card-icon">
                </div>
                <h3>Manage Scholarships</h3>
            </div>
        </a>

        <a href="AppMgmt.php" class="action-card" style="text-decoration: none;">
            <div class="card-content">
                <div class="icon-wrapper">
                    <img src="../icons/REVIEW APPLICATIONS.png" alt="Review Applications" class="card-icon">
                </div>
                <h3>Review Applications</h3>
            </div>
        </a>
    </div>

    <div class="action-row single">
        <a href="statistics_reports.php" class="action-card" style="text-decoration: none;">
            <div class="card-content">
                <div class="icon-wrapper">
                    <img src="../icons/STATS AND REPORTS.png" alt="Statistics & Reports" class="card-icon">
                </div>
                <h3>Statistics & Reports</h3>
            </div>
        </a>
    </div>
</section>

</body>
    </html>
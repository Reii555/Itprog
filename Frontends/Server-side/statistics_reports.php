<?php

//  STATISTICS & REPORTS PAGE

//  Provides admins reports and insights on both scholarships and applications, with visuals and sorted tables.
//  Features include:
//     - Statistics section with total scholarships, total applicants, application status distribution, 
//       and applications per scholarship (with bar graphs)
//     - Reports section with separate tables for scholarships, applications, and approved scholars
//       sorted by most recent activity, and displays relevant details for each entry.
// onion-40
session_start();
require '../../db_connect.php';

// checks if the user is logged in, if not redirects to login page
if(!isset($_SESSION['account_id'])){
    header("Location: ./login.php");
    exit();
}

//get admin ID
$admin_id = null;
$admin_name = '';
$admin_query = mysqli_query($conn, "SELECT admin_id, first_name, last_name FROM administrators WHERE account_id = '" .
                mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'");
if($admin_query && mysqli_num_rows($admin_query) > 0){
    $admin_data = mysqli_fetch_assoc($admin_query);
    $admin_id = $admin_data['admin_id'];
    $admin_name = $admin_data['first_name'] . ' ' . $admin_data['last_name'];
}

// ============================================
// GET STATISTICS DATA
// ============================================
//total scholarships
$total_scholarships_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM scholarships");
$total_scholarships = mysqli_fetch_assoc($total_scholarships_query)['total'];

//total applicants (unique in students who applied)
$total_applicants_query = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as total FROM applications");
$total_applicants = mysqli_fetch_assoc($total_applicants_query)['total'];

//application status for bar graph
$status_count_query = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM applications GROUP BY status");
$status_data = [];
while($row = mysqli_fetch_assoc($status_count_query)){
    $status_data[$row['status']] = $row['count'];
}

//applications per scholarship for bar graph
$app_per_schol = "SELECT s.title, COUNT(a.application_id) as applicant_count
                    FROM scholarships s
                    LEFT JOIN applications a ON s.scholarship_id = a.scholarship_id
                    GROUP BY s.scholarship_id";
$app_per_schol_query = mysqli_query($conn, $app_per_schol);
$scholarship_applications = [];
while($row = mysqli_fetch_assoc($app_per_schol_query)){
    $scholarship_applications[] = $row;
}

// ============================================
// GET REPORTS DATA
// ============================================
//scholarship report
$schol_report = "SELECT s.scholarship_id, s.title, s.deadline, s.status, 
                            COUNT(DISTINCT a.application_id) as total_applicants,
                            COUNT(DISTINCT CASE WHEN a.status = 'Approved' THEN a.student_id END) as approved_count
                        FROM scholarships s
                        LEFT JOIN applications a ON s.scholarship_id = a.scholarship_id
                        GROUP BY s.scholarship_id
                        ORDER BY s.created_at DESC";
$schol_report_query = mysqli_query($conn, $schol_report);
$schol_report_data = [];
while($row = mysqli_fetch_assoc($schol_report_query)){
    $schol_report_data[] = $row;
}

//application report
$app_report = "SELECT a.application_id, a.status as application_status, a.submission_date,
                    CONCAT(s.first_name, ' ', s.last_name) as applicant_name,
                    s.year_level, s.department, sch.title as scholarship_title, sch.deadline as scholarship_deadline
                FROM applications a
                JOIN students s ON a.student_id = s.student_id
                JOIN scholarships sch ON a.scholarship_id = sch.scholarship_id
                ORDER BY a.submission_date DESC";
$app_report_query = mysqli_query($conn, $app_report);
$app_report_data = [];
while($row = mysqli_fetch_assoc($app_report_query)){
    $app_report_data[] = $row;
}

//approved scholars report
$approved_report = "SELECT a.application_id, a.submission_date,
                        CONCAT(s.first_name, ' ', s.last_name) as applicant_name,
                        s.year_level, s.department, sch.title as scholarship_title, a.status as application_status
                    FROM applications a
                    JOIN students s ON a.student_id = s.student_id
                    JOIN scholarships sch ON a.scholarship_id = sch.scholarship_id
                    WHERE a.status = 'Approved'
                    ORDER BY a.submission_date DESC";
$approved_report_query = mysqli_query($conn, $approved_report);
$approved_scholars = [];
while($row = mysqli_fetch_assoc($approved_report_query)){
    $approved_scholars[] = $row;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Statistics & Reports</title>
        <link rel="stylesheet" href="stats_report_design.css">
    </head>
    <body>
        <header class="header">
    <img src="../../Frontends/icons/CSP_logo.png" alt="CSP Logo" class="CSP-logo">
    <strong>Centralized Scholarship Portal</strong>
    <span> / <a href="Server_Dashboard.php">Dashboard</a> / Statistics & Reports</span>
        </header>

        <div class="container">
            <h1>Statistics</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <strong>Total Scholarships</strong>
                    <p><?php echo $total_scholarships; ?></p>
                </div>
                <div class="stat-card">
                    <strong>Total Applicants</strong>
                    <p><?php echo $total_applicants; ?></p>
                </div>
            </div>
            
            <div class="charts-section">
                <div class="chart-card">
                    <h3>Application Status</h3>
                    <div class="chart-placeholder">
                        <!--  -->
                        <?php if(empty($status_data)): ?>
                            <p>No application data available.</p>
                        <?php else: ?>
                            <?php 
                            $total_applications = array_sum($status_data);
                            $colors = ['#4caf50', '#ffc107', '#f44336', '#2196f3', '#9c27b0'];
                            $color_index = 0;
                            foreach($status_data as $status => $count):
                                $percentage = ($count / $total_applications) * 100;
                                $color = $colors[$color_index % count($colors)];
                            ?>
                            <div class="chart-item">
                                <div class="chart-label">
                                    <span><?php echo ucfirst($status); ?></span>
                                    <span><?php echo $count; ?> (<?php echo round($percentage, 1); ?>%)</span>
                                </div>
                                <div class="chart-bar">
                                    <div class="chart-fill" style="width: <?php echo $percentage; ?>%; background: <?php echo $color; ?>;">
                                        <?php if($percentage > 10): ?>
                                            <?php echo round($percentage, 1); ?>%
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $color_index++; endforeach; ?>
                            <p style="margin-top: 15px; font-size: 12px; color: #666;">Total Applications: <?php echo $total_applications; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>Applications per Scholarship</h3>
                    <div class="chart-placeholder">
                        <?php if(empty($scholarship_applications)): ?>
                            <p>No scholarship application data available.</p>
                        <?php else: ?>
                            <?php 
                            $max_applicants = max(array_column($scholarship_applications, 'applicant_count'));
                            $max_applicants = $max_applicants > 0 ? $max_applicants : 1;
                            foreach($scholarship_applications as $item):
                                $percentage = ($item['applicant_count'] / $max_applicants) * 100;
                            ?>
                            <div class="chart-item">
                                <div class="chart-label">
                                    <span><?php echo htmlspecialchars(substr($item['title'], 0, 30)); if(strlen($item['title']) > 30) echo '...'; ?></span>
                                    <span><?php echo $item['applicant_count']; ?> applicants</span>
                                </div>
                                <div class="chart-bar">
                                    <div class="chart-fill" style="width: <?php echo $percentage; ?>%;">
                                        <?php if($percentage > 15): ?>
                                            <?php echo $item['applicant_count']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="container">
            <h1>Reports</h1>
            
            <h2>Scholarships</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Scholarship</th>
                            <th>Applicants</th>
                            <th>Approved</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($schol_report_data)): ?>
                            <?php foreach($schol_report_data as $scholarship): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($scholarship['title']); ?></strong></td>
                                    <td><?php echo $scholarship['total_applicants']; ?></td>
                                    <td><?php echo $scholarship['approved_count']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($scholarship['deadline'])); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($scholarship['status']); ?>"><?php echo $scholarship['status']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="6">No scholarship data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h2>Applications</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Year Level</th>
                            <th>Department</th>
                            <th>Scholarship</th>
                            <th>Status</th>
                            <th>Date Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($app_report_data)): ?>
                            <?php foreach($app_report_data as $application): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($application['applicant_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($application['year_level']); ?></td>
                                    <td><?php echo htmlspecialchars($application['department']); ?></td>
                                    <td><?php echo htmlspecialchars($application['scholarship_title']); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($application['application_status']); ?>"><?php echo $application['application_status']; ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($application['submission_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="6">No application data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h2>Approved Scholars</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Year Level</th>
                            <th>Department</th>
                            <th>Scholarship</th>
                            <th>Date Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($approved_scholars)): ?>
                            <?php foreach($approved_scholars as $scholar): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($scholar['applicant_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($scholar['year_level']); ?></td>
                                    <td><?php echo htmlspecialchars($scholar['department']); ?></td>
                                    <td><?php echo htmlspecialchars($scholar['scholarship_title']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($scholar['submission_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="5">No approved scholars yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
<?php
session_start();
require '../../db_connect.php';

if(!isset($_SESSION['account_id'])){
    header("Location: ../../frontend/Server-side/login.php");
    exit();
}

$admin_id = null;
$admin_query = mysqli_query($conn, "SELECT admin_id FROM administrators WHERE account_id = '" .
                mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'");
if($admin_query && mysqli_num_rows($admin_query) > 0){
    $admin_data = mysqli_fetch_assoc($admin_query);
    $admin_id = $admin_data['admin_id'];
}

$admin_name_query = "SELECT CONCAT(first_name, ' ', last_name) as full_name FROM administrators
 WHERE account_id = '" . mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'";

if($admin_query && mysqli_num_rows($admin_query) > 0){
    $admin_data = mysqli_fetch_assoc($admin_query);
    $admin_id = $admin_data['admin_id'];
    $admin_name = $admin_data['first_name'] . ' ' . $admin_data['last_name'];
}

//Get statistics
//Total scholarships
$total_scholarships_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM scholarships");
$total_scholarships = mysqli_fetch_assoc($total_scholarships_query)['total'];

//Total applicants (unique in students who applied)
$total_applicants_query = mysqli_query($conn, "SELECT COUNT(DISTINCT student_id) as total FROM applications");
$total_applicants = mysqli_fetch_assoc($total_applicants_query)['total'];

//application status for pie chart
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

//reports
//scholarship report
$schol_report = "SELECT s.scholarship_id, s.title, s.slots, s.deadline, s.status, 
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
                JOIN students s ON a .student_id = s.student_id
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
                    JOIN student s ON a.student_id = s.student_id
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
    </head>
    <body>
        <header>
            <img src="logo.png" alt="Logo" width="60" height="60">
            <strong>Centralized Scholarship Portal</strong>
            <link rel="stylesheet" href="StatisticsReports_design.css">
            <span> / <a href="test.html">Dashboard</a> / Statistics & Reports</span>
        </header>

        <hr>

        <div class="container">
        <h1>Statistics</h1>

        <section>
            <p><strong>Total Scholarships: </strong> # </p>
            <p><strong>Total Applicants: </strong> # </p>
            
            <div>
                <h3>Application Status (pie chart)</h3>
                <div>
                    <p>Pie chart placeholder</p>
                </div>
            </div>

            <div>
                <h3>Application per Scholarships (bar graph)</h3>
                <div>
                    <p>Bar Graph placeholder</p>
                </div>
            </div>
        </section>
        </div>

        <hr>

        <h1>Reports</h1>
        
        <h3>Scholarships</h3>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Scholarships</th>
                    <th>Slots</th>
                    <th>Applicants</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Example Scholarship</td>
                    <td>50</td>
                    <td>120</td>
                    <td>2026-12-30</td>
                </tr>
            </tbody>
        </table>

        <br>

        <h3>Applications</h3>

        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Year Level</th>
                    <th>Scholarship</th>
                    <th>Date Applied</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Dela Cruz</td>
                    <td>3rd Year</td>
                    <td>lasal scholarship</td>
                    <td>Date applied</td>
                </tr>
            </tbody>
        </table>
        
        <br>

        <h3>Approved Scholars</h3>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Year Level</th>
                    <th>Scholarship</th>
                    <th>Date Approved</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Maria Leonora Theresa</td>
                    <td>2nd Year</td>
                    <td>St. Lasalle Financial Assistance Grant</td>
                    <td>03-11-2026</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
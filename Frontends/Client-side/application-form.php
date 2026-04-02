<?php
// CLIENT-SIDE
// Displays the scholarship application form with auto-filled user data
// @alledelweiss

session_start();
include("../../db_connect.php");

if(!isset($_SESSION['account_id'])){
    header("Location: login.php");
    exit();
}

$account_id = $_SESSION['account_id'];

$getStudID = mysqli_query($conn, "SELECT student_id FROM STUDENTS WHERE account_id='$account_id'");

if ($getStudID && mysqli_num_rows($getStudID) > 0) {
    $row = mysqli_fetch_assoc($getStudID);
    $student_id = $row['student_id'];
} else {
    die("Student record not found.");
}

$scholarship_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// if no scholarship ID provided, redirect to scholarships page
if ($scholarship_id == 0) {
    header('Location: scholarships.php');
    exit();
}

// get student data
$student_query = "SELECT s.*, a.email 
                 FROM STUDENTS s
                 JOIN ACCOUNTS a ON s.account_id = a.account_id
                 WHERE s.student_id = $student_id";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

// get scholarship details
$scholarship_query = "SELECT * FROM SCHOLARSHIPS WHERE scholarship_id = $scholarship_id";
$scholarship_result = mysqli_query($conn, $scholarship_query);
$scholarship = mysqli_fetch_assoc($scholarship_result);

if (!$scholarship) {
    header('Location: scholarships.php');
    exit();
}

// check if student has already applied
$check_query = "SELECT * FROM APPLICATIONS 
                WHERE student_id = $student_id 
                AND scholarship_id = $scholarship_id";
$check_result = mysqli_query($conn, $check_query);
$existing_application = mysqli_fetch_assoc($check_result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_date = date('Y-m-d H:i:s');
    
    if (isset($_POST['submit_application'])) {
        if ($existing_application) {
            // updates the existing application
            $update_query = "UPDATE APPLICATIONS SET 
                            status = 'Submitted',
                            submission_date = '$current_date'
                            WHERE application_id = {$existing_application['application_id']}";
            mysqli_query($conn, $update_query);
        } else {
            // creates a new application
            $insert_query = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                            VALUES ($student_id, $scholarship_id, 'Submitted', '$current_date')";
            mysqli_query($conn, $insert_query);
            $application_id = mysqli_insert_id($conn);
        }  
        echo "<script>alert('Application submitted successfully!'); 
            window.location.href='your-applications.php';</script>";
        exit();
    } elseif (isset($_POST['save_draft'])) {
        if ($existing_application) {
            // updates the existing draft
            $update_query = "UPDATE APPLICATIONS SET 
                            status = 'Draft'
                            WHERE application_id = {$existing_application['application_id']}";
            mysqli_query($conn, $update_query);
        } else {
            // creates a new draft
            $insert_query = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status) 
                            VALUES ($student_id, $scholarship_id, 'Draft')";
            mysqli_query($conn, $insert_query);
        }
        echo "<script>alert('Application saved as draft!'); 
            window.location.href='your-applications.php';</script>";
        exit();
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Application Form</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <section class="brand">
                <img src="../icons/CSP_logo.png" id="logo" alt="logo icon" onerror="this.style.display='none'">
                <h1>Centralized Scholarship Portal</h1>
            </section>
            <nav>
                <a href="home.php">Home</a>
                <a href="scholarList.php">Scholarships</a>
                <a href="your-applications.php">Your Applications</a>
                <a href="user-profile.php">Profile</a>
            </nav>
        </header>

        <div class="container">
            <h2><?php echo htmlspecialchars($scholarship['title']); ?> - Application Form</h2>
            <p><strong>Deadline:</strong> <?php echo date('F d, Y', strtotime($scholarship['deadline'])); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($scholarship['status']); ?></p>

            <section class="scholarship-description">
                <h3>Scholarship Description</h3>
                <p><?php echo nl2br(htmlspecialchars($scholarship['description'] ?? 'No description available.')); ?></p>
            </section>

            <form method="POST" action="" enctype="multipart/form-data">
                <section>
                    <p><strong>Application ID:</strong> <?php echo $existing_application['application_id'] ?? '--- (Will be generated upon submission)'; ?></p>
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>

                    <h3>Personal Information</h3>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <p><strong>First Name</strong></p>
                            <p><?php echo htmlspecialchars($student['first_name']); ?></p>
                        </div>
                        <div class="profile-item">
                            <p><strong>Last Name</strong></p>
                            <p><?php echo htmlspecialchars($student['last_name']); ?></p>
                        </div>
                    </div>

                    <div class="profile-grid">
                        <div class="profile-item">
                            <p><strong>Email</strong></p>
                            <p><?php echo htmlspecialchars($student['email']); ?></p>
                        </div>
                        <div class="profile-item">
                            <p><strong>Contact Number</strong></p>
                            <p>0<?php echo htmlspecialchars($student['contact_num']); ?></p>
                        </div>
                    </div>

                    <div class="profile-grid">
                        <div class="profile-item">
                            <p><strong>Department</strong></p>
                            <p><?php echo htmlspecialchars($student['department']); ?></p>
                        </div>
                        <div class="profile-item">
                            <p><strong>Year Level</strong></p>
                            <p><?php echo htmlspecialchars($student['year_level']); ?></p>
                        </div>
                    </div>

                    <h3>Required Documents</h3>
                    <ul class="requirements-list">
                        <li>Certificate of Enrollment</li>
                        <li>Grades from previous semester</li>
                        <li>Letter of Recommendation</li>
                        <li>Valid ID</li>
                        <li>2x2 Picture</li>
                    </ul>

                    <div class="form-group">
                        <label for="document_type"><strong>Document Type</strong></label>
                        <select id="document_type" name="document_type" class="form-control">
                            <option value="">Select Document Type</option>
                            <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                            <option value="Grades">Grades</option>
                            <option value="Recommendation Letter">Recommendation Letter</option>
                            <option value="Valid ID">Valid ID</option>
                            <option value="Picture">2x2 Picture</option>
                        </select>
                    </div>

                    <div class="file-upload">
                        <input type="file" id="file_upload" name="file_upload" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <label for="file_upload">+ Upload File</label>
                    </div>
                </section>

                <div class="action-links">
                    <a href="scholarships.php" class="btn btn-secondary">Go Back</a>
                    <button type="submit" name="save_draft" class="btn">Save as Draft</button>
                    <button type="submit" name="submit_application" class="btn">Submit Application</button>
                </div>
            </form>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

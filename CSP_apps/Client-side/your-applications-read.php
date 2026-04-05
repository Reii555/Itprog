<?php
// your-applications-read.php
// CLIENT-SIDE
// Displays a single application with full details
// @alledelweiss

session_start();
require("../../db_connect.php");

if(!isset($_SESSION['student_acc_id'])){
    header("Location: login.php");
    exit();
}

$account_id = $_SESSION['student_acc_id'];

$getStudID = mysqli_query($conn, "SELECT student_id FROM STUDENTS WHERE account_id='$account_id'");
if ($getStudID && mysqli_num_rows($getStudID) > 0) {
    $row = mysqli_fetch_assoc($getStudID);
    $student_id = $row['student_id'];
} else {
    die("Student record not found.");
}

$application_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT a.*, s.title as scholarship_name, s.deadline, s.description,
          st.first_name, st.last_name, st.department, st.year_level, st.contact_num,
          acc.email, adm.email as reviewer_email
          FROM APPLICATIONS a
          JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id
          JOIN STUDENTS st ON a.student_id = st.student_id
          JOIN ACCOUNTS acc ON st.account_id = acc.account_id
          LEFT JOIN ADMINISTRATORS admin ON a.reviewed_by = admin.admin_id
          LEFT JOIN ACCOUNTS adm ON admin.account_id = adm.account_id
          WHERE a.application_id = $application_id AND a.student_id = $student_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$application = mysqli_fetch_assoc($result);

if (!$application) {
    header('Location: your-applications.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Application Details</title>
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
            <section>
                <h2><?php echo htmlspecialchars($application['scholarship_name']); ?></h2>
                <p><strong>Status:</strong> 
                    <span class="status-<?php echo strtolower(str_replace(' ', '', $application['status'] ?? 'pending')); ?>">
                        <?php echo htmlspecialchars($application['status'] ?? 'Pending'); ?>
                    </span>
                </p>
                
                <div class="profile-grid">
                    <div class="profile-item">
                        <p><strong>First Name:</strong></p>
                        <p><?php echo htmlspecialchars($application['first_name']); ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Last Name:</strong></p>
                        <p><?php echo htmlspecialchars($application['last_name']); ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Year Level:</strong></p>
                        <p><?php echo htmlspecialchars($application['year_level'] ?? 'Not set'); ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Contact Number:</strong></p>
                        <p>0<?php echo htmlspecialchars($application['contact_num'] ?? 'Not set'); ?></p>
                    </div>
                </div>
                
                <div class="profile-grid">
                    <div class="profile-item">
                        <p><strong>Email:</strong></p>
                        <p><?php echo htmlspecialchars($application['email']); ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Department:</strong></p>
                        <p><?php echo htmlspecialchars($application['department']); ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Scholarship ID:</strong></p>
                        <p><?php echo htmlspecialchars($application['scholarship_id']); ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Application ID:</strong></p>
                        <p><?php echo htmlspecialchars($application['application_id']); ?></p>
                    </div>
                </div>

                <div class="profile-grid">
                    <div class="profile-item">
                        <p><strong>Submission Date:</strong></p>
                        <p><?php echo $application['submission_date'] ? date('M d, Y', strtotime($application['submission_date'])) : 'Not submitted'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Deadline:</strong></p>
                        <p><?php echo date('M d, Y', strtotime($application['deadline'])); ?></p>
                    </div>
                </div>

                <div class="profile-item" style="grid-column: span 2;">
                    <p><strong>Scholarship Description:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($application['description'] ?? 'No description available.')); ?></p>
                </div>

                <div class="profile-grid">
                    <div class="profile-item">
                        <p><strong>Reviewed by:</strong></p>
                        <p><?php echo $application['reviewer_email'] ? 'Email: ' . $application['reviewer_email'] : 'Not reviewed'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Date reviewed:</strong></p>
                        <p><?php echo $application['review_date'] ? date('M d, Y', strtotime($application['review_date'])) : 'Not reviewed'; ?></p>
                    </div>
                </div>

                <div class="action-links">
                    <a href="your-applications.php" class="btn">Go Back</a>
                    
                    <?php if ($application['status'] == 'Draft'): ?>
                        <!-- only show edit button if status is draft -->
                        <a href="application-form.php?id=<?php echo $application['scholarship_id']; ?>" class="btn btn-secondary">Edit</a>
                    <?php else: ?>
                        <span class="btn btn-secondary disabled" style="opacity: 0.5; cursor: not-allowed;" title="Cannot edit - application already <?php echo strtolower($application['status']); ?>">Edit</span>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

<?php
// CLIENT-SIDE
// Displays a single application with full details
// @alledelweiss

session_start();
include("../../db_connect.php");

$application_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT a.*, s.title as scholarship_name, s.deadline, s.description,
          st.first_name, st.last_name, st.department, st.year_level, st.contact_num,
          acc.email
          FROM APPLICATIONS a
          JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id
          JOIN STUDENTS st ON a.student_id = st.student_id
          JOIN ACCOUNTS acc ON st.account_id = acc.account_id
          WHERE a.application_id = $application_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn) . " - Query: " . $query);
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
                        <p><?php echo $application['reviewed_by'] ? 'Admin ID: ' . $application['reviewed_by'] : 'Not reviewed'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p><strong>Date reviewed:</strong></p>
                        <p><?php echo $application['review_date'] ? date('M d, Y', strtotime($application['review_date'])) : 'Not reviewed'; ?></p>
                    </div>
                </div>

                <div class="action-links">
                    <a href="your-applications.php" class="btn">Go Back</a>
                    <a href="application-form.php?id=<?php echo $application['scholarship_id']; ?>" class="btn btn-secondary">Edit</a>
                </div>
            </section>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

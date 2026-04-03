<?php
// CLIENT-SIDE
// Displays the student's application forms and their details
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

// get student's applications
$query = "SELECT a.*, s.title as scholarship_name, s.deadline 
          FROM APPLICATIONS a 
          JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id 
          WHERE a.student_id = $student_id 
          ORDER BY a.submission_date DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Your Applications</title>
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
                <a id="active" href="your-applications.php">Your Applications</a>
                <a href="user-profile.php">Profile</a>
            </nav>
        </header>

        <div class="container">
            <h2>Your Applications</h2>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($application = mysqli_fetch_assoc($result)): ?>
                    <section class="application-card">
                        <h3><?php echo htmlspecialchars($application['scholarship_name']); ?></h3>
                        <div class="application-details">
                            <p><strong>Status:</strong> 
                                <span class="status-<?php echo strtolower(str_replace(' ', '', $application['status'] ?? 'pending')); ?>">
                                    <?php echo htmlspecialchars($application['status'] ?? 'Pending'); ?>
                                </span>
                            </p>
                            <p><strong>Deadline:</strong> <?php echo date('M d, Y', strtotime($application['deadline'])); ?></p>
                            <p><strong>Submission Date:</strong> <?php echo $application['submission_date'] ? date('M d, Y', strtotime($application['submission_date'])) : 'Not submitted'; ?></p>
                            <p><strong>Scholarship ID:</strong> <?php echo htmlspecialchars($application['scholarship_id']); ?></p>
                        </div>
                        <div class="application-actions">
                            <a href="your-applications-read.php?id=<?php echo $application['application_id']; ?>" class="btn">View Application</a>
                            
                            <?php if ($application['status'] == 'Draft'): ?>
                                <!-- edit and delete buttons if status is draft -->
                                <a href="application-form.php?id=<?php echo $application['scholarship_id']; ?>" class="btn btn-secondary">Edit Application</a>
                                <a href="your-applications-delete.php?id=<?php echo $application['application_id']; ?>" class="btn btn-danger">Delete Application</a>
                            <?php else: ?>
                                <span class="btn btn-secondary disabled" style="opacity: 0.5; cursor: not-allowed;" title="Cannot edit - application already <?php echo strtolower($application['status']); ?>">Edit Application</span>
                                <span class="btn btn-danger disabled" style="opacity: 0.5; cursor: not-allowed;" title="Cannot delete - application already <?php echo strtolower($application['status']); ?>">Delete Application</span>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endwhile; ?>
            <?php else: ?>
                <section>
                    <p>You haven't submitted any applications yet.</p>
                    <a href="scholarList.php" class="btn">Browse Scholarships</a>
                </section>
            <?php endif; ?>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

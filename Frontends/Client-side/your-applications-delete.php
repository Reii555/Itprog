<?php
// CLIENT-SIDE
// Displays applications that can be deleted with confirmation
// @alledelweiss

include 'db_connect.php';

$student_id = $_SESSION['student_id'];

if (isset($_POST['delete_application'])) {
    $application_id = (int)$_POST['application_id'];
    
    // check first if the application belongs to the student
    $check_query = "SELECT application_id FROM APPLICATIONS 
                    WHERE application_id = $application_id 
                    AND student_id = $student_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // delete first related documents
        mysqli_query($conn, "DELETE FROM DOCUMENTS 
                             WHERE application_id = $application_id");
        
        // then delete the application
        $delete_query = "DELETE FROM APPLICATIONS 
                         WHERE application_id = $application_id 
                         AND student_id = $student_id";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Application deleted successfully!'); 
            window.location.href='your-applications.php';</script>";
        }
    }
}

// get the student's applications
$query = "SELECT a.*, s.title as scholarship_name, s.deadline 
          FROM APPLICATIONS a 
          JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id 
          WHERE a.student_id = $student_id 
          ORDER BY a.submission_date DESC";
$result = mysqli_query($conn, $query);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Delete Applications</title>
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
            <h3>Select application to remove</h3> 

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($application = mysqli_fetch_assoc($result)): ?>
                    <section class="application-card">
                        <h3><?php echo htmlspecialchars($application['scholarship_name']); ?></h3>
                        <div class="application-details">
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($application['status'] ?? 'Pending'); ?></p>
                            <p><strong>Deadline:</strong> <?php echo date('M d, Y', strtotime($application['deadline'])); ?></p>
                            <p><strong>Submission Date:</strong> <?php echo $application['submission_date'] ? date('M d, Y', strtotime($application['submission_date'])) : 'Not submitted'; ?></p>
                            <p><strong>Scholarship ID:</strong> <?php echo htmlspecialchars($application['scholarship_id']); ?></p>
                        </div>
                        
                        <!-- delete confirmation form -->
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                            <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                            <button type="submit" name="delete_application" class="btn btn-danger">Delete this application</button>
                        </form>
                    </section>
                <?php endwhile; ?>
            <?php else: ?>
                <section>
                    <p>No applications to delete.</p>
                </section>
            <?php endif; ?>

            <div class="action-links">
                <a href="your-applications.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

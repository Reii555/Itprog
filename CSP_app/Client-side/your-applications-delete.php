<?php
// your-applications-delete.php
// CLIENT-SIDE
// Deletes application only if status is Draft
// @alledelweiss

session_start();
include("../../db_connect.php");

if(!isset($_SESSION['student_acc_id'])){
    header("Location: login.php");
    exit();
}

$account_id = $_SESSION['student_acc_id'];
$application_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$getStudID = mysqli_query($conn, "SELECT student_id FROM STUDENTS WHERE account_id='$account_id'");
if ($getStudID && mysqli_num_rows($getStudID) > 0) {
    $row = mysqli_fetch_assoc($getStudID);
    $student_id = $row['student_id'];
} else {
    die("Student record not found.");
}

// check if application exists, belongs to student, and has draft status
$check_query = "SELECT a.*, s.title as scholarship_name 
                FROM APPLICATIONS a 
                JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id
                WHERE a.application_id = $application_id AND a.student_id = $student_id";
$check_result = mysqli_query($conn, $check_query);
$application = mysqli_fetch_assoc($check_result);

if (!$application) {
    header("Location: your-applications.php");
    exit();
}

// handles deletion
if (isset($_POST['confirm_delete'])) {
    // verify again that status is fraft
    if ($application['status'] == 'Draft') {
        // delete related documents first
        mysqli_query($conn, "DELETE FROM DOCUMENTS WHERE application_id = $application_id");
        
        // then delete the application
        $delete_query = "DELETE FROM APPLICATIONS WHERE application_id = $application_id AND student_id = $student_id";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Application deleted successfully!'); window.location.href='your-applications.php';</script>";
            exit();
        } else {
            $error = "Failed to delete application.";
        }
    } else {
        $error = "Cannot delete application. Status is not Draft.";
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Delete Application</title>
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
            <h2>Delete Application</h2>

            <?php if ($application['status'] == 'Draft'): ?>
                <section class="delete-confirmation">
                    <h3>Are you sure you want to delete this application?</h3>
                    <p><strong>Scholarship:</strong> <?php echo htmlspecialchars($application['scholarship_name']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($application['status']); ?></p>
                    <p><strong>Submission Date:</strong> <?php echo $application['submission_date'] ? date('M d, Y', strtotime($application['submission_date'])) : 'Not submitted'; ?></p>
                    
                    <p style="color: #bc2f2f; margin-top: 15px;">This action cannot be undone.</p>
                    
                    <form method="POST" style="margin-top: 20px;">
                        <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                        <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete Application</button>
                        <a href="your-applications.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </section>
            <?php else: ?>
                <section>
                    <h3 style="color: #bc2f2f;">Cannot Delete Application</h3>
                    <p>This application has a status of <strong><?php echo htmlspecialchars($application['status']); ?></strong>.</p>
                    <p>Only applications with "Draft" status can be deleted.</p>
                    <a href="your-applications.php" class="btn">Go Back</a>
                </section>
            <?php endif; ?>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

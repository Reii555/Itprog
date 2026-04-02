<?php
// CLIENT-SIDE
// Displays the student's profile and its details
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

$query = "SELECT s.*, a.email 
          FROM STUDENTS s
          JOIN ACCOUNTS a ON s.account_id = a.account_id
          WHERE s.student_id = $student_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$student = mysqli_fetch_assoc($result);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Profile</title>
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
                <a id="active" href="user-profile.php">Profile</a>
            </nav>
        </header>

        <div class="container">
            <h2>Account Information</h2>

            <section>
                <div class="profile-grid">
                    <div class="profile-item">
                        <p>First Name</p>
                        <p><?php echo isset($student['first_name']) ? htmlspecialchars($student['first_name']) : 'Not set'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p>Last Name</p>
                        <p><?php echo isset($student['last_name']) ? htmlspecialchars($student['last_name']) : 'Not set'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p>Email</p>
                        <p><?php echo isset($student['email']) ? htmlspecialchars($student['email']) : 'Not set'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p>Contact Number</p>
                        <p><?php echo isset($student['contact_num']) ? '0' . htmlspecialchars($student['contact_num']) : 'Not set'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p>Department</p>
                        <p><?php echo isset($student['department']) ? htmlspecialchars($student['department']) : 'Not set'; ?></p>
                    </div>
                    <div class="profile-item">
                        <p>Year Level</p>
                        <p><?php echo isset($student['year_level']) ? htmlspecialchars($student['year_level']) : 'Not set'; ?></p>
                    </div>
                </div>
            </section>

            <div class="action-links">
                <a href="user-profile-update.php" class="btn">Edit</a>
                <a href="home.php" class="btn btn-secondary">Go back</a>
            </div>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

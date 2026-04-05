<?php
// CLIENT-SIDE
// Displays the student's profile update form
// @alledelweiss

session_start();
include("../../db_connect.php");

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

$query = "SELECT s.*, a.email, a.account_id 
          FROM STUDENTS s
          JOIN ACCOUNTS a ON s.account_id = a.account_id
          WHERE s.student_id = $student_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_num = mysqli_real_escape_string($conn, $_POST['contact_num']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);
    
    // update STUDENTS table
    $update_student = "UPDATE STUDENTS SET 
                      first_name = '$first_name',
                      last_name = '$last_name',
                      department = '$department',
                      year_level = '$year_level',
                      contact_num = '$contact_num'
                      WHERE student_id = $student_id";
    
    // update ACCOUNTS table
    $update_account = "UPDATE ACCOUNTS SET 
                      email = '$email'
                      WHERE account_id = {$student['account_id']}";
    
    if (mysqli_query($conn, $update_student) && mysqli_query($conn, $update_account)) {
        header('Location: user-profile.php');
        exit();
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program - Update Profile</title>
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

            <form method="POST" action="">
                <section>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo isset($student['first_name']) ? htmlspecialchars($student['first_name']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo isset($student['last_name']) ? htmlspecialchars($student['last_name']) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo isset($student['email']) ? htmlspecialchars($student['email']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_num">Contact Number</label>
                            <input type="text" id="contact_num" name="contact_num" value="<?php echo isset($student['contact_num']) ? htmlspecialchars($student['contact_num']) : ''; ?>" required placeholder="912345678">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" id="department" name="department" value="<?php echo isset($student['department']) ? htmlspecialchars($student['department']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="year_level">Year Level</label>
                            <select id="year_level" name="year_level" required>
                                <option value="">Select Year Level</option>
                                <option value="Freshman" <?php echo (isset($student['year_level']) && $student['year_level'] == 'Freshman') ? 'selected' : ''; ?>>Freshman</option>
                                <option value="Sophomore" <?php echo (isset($student['year_level']) && $student['year_level'] == 'Sophomore') ? 'selected' : ''; ?>>Sophomore</option>
                                <option value="Junior" <?php echo (isset($student['year_level']) && $student['year_level'] == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                                <option value="Senior" <?php echo (isset($student['year_level']) && $student['year_level'] == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                            </select>
                        </div>
                    </div>
                </section>

                <div class="action-links">
                    <button type="submit" class="btn">Save</button>
                    <a href="user-profile.php" class="btn btn-secondary">Go Back</a>
                </div>
            </form>
        </div>

        <footer>
            <p>&copy; 2026 Centralized Scholarship Program</p>
        </footer>
    </body>
</html>

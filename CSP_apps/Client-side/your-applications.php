<?php
// your-applications.php
// CLIENT-SIDE
// Displays the student's application forms and their details with sorting and filtering
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

// filter and sort parameters
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'all';
$sort_by = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : 'date_desc';

// build WHERE clause for filtering
$where_clause = "a.student_id = $student_id";
if ($status_filter != 'all') {
    $where_clause .= " AND a.status = '$status_filter'";
}

// build ORDER BY clause for sorting
switch ($sort_by) {
    case 'date_asc':
        $order_by = "a.submission_date ASC";
        break;
    case 'status_asc':
        $order_by = "FIELD(a.status, 'Draft', 'Submitted', 'Under Review', 'Waitlisted', 'Approved', 'Rejected') ASC";
        break;
    case 'status_desc':
        $order_by = "FIELD(a.status, 'Rejected', 'Approved', 'Waitlisted', 'Under Review', 'Submitted', 'Draft') ASC";
        break;
    case 'scholarship_asc':
        $order_by = "s.title ASC";
        break;
    case 'scholarship_desc':
        $order_by = "s.title DESC";
        break;
    case 'date_desc':
    default:
        $order_by = "a.submission_date DESC";
        break;
}

// get student's applications with filters and sorting
$query = "SELECT a.*, s.title as scholarship_name, s.deadline 
          FROM APPLICATIONS a 
          JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id 
          WHERE $where_clause 
          ORDER BY $order_by";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// get counts for each status
$status_counts = [];
$status_query = "SELECT a.status, COUNT(*) as count 
                 FROM APPLICATIONS a 
                 WHERE a.student_id = $student_id 
                 GROUP BY a.status";
$status_result = mysqli_query($conn, $status_query);
while ($row = mysqli_fetch_assoc($status_result)) {
    $status_counts[$row['status']] = $row['count'];
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
            
            <!-- status filter bar -->
            <div class="filter-bar">
                <div class="filter-header">
                    <h3>Filter by Status</h3>
                </div>
                <div class="status-filters">
                    <a href="?status=all&sort=<?php echo $sort_by; ?>" class="status-filter <?php echo $status_filter == 'all' ? 'active' : ''; ?>">
                        All 
                        <span class="count"><?php echo array_sum($status_counts); ?></span>
                    </a>
                    <a href="?status=Draft&sort=<?php echo $sort_by; ?>" class="status-filter draft <?php echo $status_filter == 'Draft' ? 'active' : ''; ?>">
                        Draft 
                        <span class="count"><?php echo $status_counts['Draft'] ?? 0; ?></span>
                    </a>
                    <a href="?status=Submitted&sort=<?php echo $sort_by; ?>" class="status-filter submitted <?php echo $status_filter == 'Submitted' ? 'active' : ''; ?>">
                        Submitted 
                        <span class="count"><?php echo $status_counts['Submitted'] ?? 0; ?></span>
                    </a>
                    <a href="?status=Under Review&sort=<?php echo $sort_by; ?>" class="status-filter under-review <?php echo $status_filter == 'Under Review' ? 'active' : ''; ?>">
                        Under Review 
                        <span class="count"><?php echo $status_counts['Under Review'] ?? 0; ?></span>
                    </a>
                    <a href="?status=Approved&sort=<?php echo $sort_by; ?>" class="status-filter approved <?php echo $status_filter == 'Approved' ? 'active' : ''; ?>">
                        Approved 
                        <span class="count"><?php echo $status_counts['Approved'] ?? 0; ?></span>
                    </a>
                    <a href="?status=Rejected&sort=<?php echo $sort_by; ?>" class="status-filter rejected <?php echo $status_filter == 'Rejected' ? 'active' : ''; ?>">
                        Rejected 
                        <span class="count"><?php echo $status_counts['Rejected'] ?? 0; ?></span>
                    </a>
                    <a href="?status=Waitlisted&sort=<?php echo $sort_by; ?>" class="status-filter waitlisted <?php echo $status_filter == 'Waitlisted' ? 'active' : ''; ?>">
                        Waitlisted 
                        <span class="count"><?php echo $status_counts['Waitlisted'] ?? 0; ?></span>
                    </a>
                </div>
            </div>
            
            <!-- sort options -->
            <div class="sort-bar">
                <label>Sort by:</label>
                <select id="sort-select" onchange="window.location.href='?status=<?php echo $status_filter; ?>&sort='+this.value">
                    <option value="date_desc" <?php echo $sort_by == 'date_desc' ? 'selected' : ''; ?>>Latest First</option>
                    <option value="date_asc" <?php echo $sort_by == 'date_asc' ? 'selected' : ''; ?>>Oldest First</option>
                    <option value="status_asc" <?php echo $sort_by == 'status_asc' ? 'selected' : ''; ?>>Status (Draft to Rejected)</option>
                    <option value="status_desc" <?php echo $sort_by == 'status_desc' ? 'selected' : ''; ?>>Status (Rejected to Draft)</option>
                    <option value="scholarship_asc" <?php echo $sort_by == 'scholarship_asc' ? 'selected' : ''; ?>>Scholarship Name (A-Z)</option>
                    <option value="scholarship_desc" <?php echo $sort_by == 'scholarship_desc' ? 'selected' : ''; ?>>Scholarship Name (Z-A)</option>
                </select>
            </div>

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
                                <a href="application-form.php?id=<?php echo $application['application_id']; ?>" class="btn btn-secondary">Edit Application</a>
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

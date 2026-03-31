<?php
//session_start();
include("../../db_connect.php");

//check login
/*if(!isset($_SESSION['account_id'])){
    header("Location: ../../frontend/Server-side/login.php");
    exit();
}*/

//get admin ID
/*$admin_id = null;
$admin_query = mysqli_query($conn, "SELECT admin_id FROM administrators WHERE account_id = '" .
                mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'");
if($admin_query && mysqli_num_rows($admin_query) > 0){
    $admin_data = mysqli_fetch_assoc($admin_query);
    $admin_id = $admin_data['admin_id'];
}*/

// Get statistics for the cards
// Total Applications
$total_apps_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM APPLICATIONS");
if ($total_apps_query) {
    $total_applications = mysqli_fetch_assoc($total_apps_query)['total'];
} else {
    $total_applications = 0;
}

// Pending Reviews
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM APPLICATIONS WHERE status = 'Pending' OR status = 'Under Review'");
if ($pending_query) {
    $pending_reviews = mysqli_fetch_assoc($pending_query)['total'];
} else {
    $pending_reviews = 0;
}

// Approved Applications
$approved_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM APPLICATIONS WHERE status = 'Approved'");
if ($approved_query) {
    $approved_applications = mysqli_fetch_assoc($approved_query)['total'];
} else {
    $approved_applications = 0;
}

// Get scholarships for the filter dropdown
$scholarships_query = mysqli_query($conn, "SELECT scholarship_id, title FROM SCHOLARSHIPS WHERE release_status = 'Published'");
$scholarships_list = [];
while($row = mysqli_fetch_assoc($scholarships_query)) {
    $scholarships_list[] = $row;
}

// Get filter values from form submission 
$status_filter = isset($_GET['status']) && $_GET['status'] != 'all' ? $_GET['status'] : '';
$year_filter = isset($_GET['year']) && $_GET['year'] != 'all' ? $_GET['year'] : '';
$scholarship_filter = isset($_GET['scholarship']) && $_GET['scholarship'] != 'all' ? (int)$_GET['scholarship'] : '';
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

// Handle clear filters
if(isset($_GET['clear']) && $_GET['clear'] == '1') {
    // Redirect to remove all GET parameters
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// Build WHERE clause for applications query
$where_conditions = [];
$params = [];
$types = "";

if($status_filter) {
    $where_conditions[] = "a.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}
if($year_filter) {
    $where_conditions[] = "s.year_level = ?";
    $params[] = $year_filter;
    $types .= "s";
}
if($scholarship_filter) {
    $where_conditions[] = "a.scholarship_id = ?";
    $params[] = $scholarship_filter;
    $types .= "i";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
$order_clause = "ORDER BY a.submission_date " . ($sort_order == 'asc' ? 'ASC' : 'DESC');

// Build the full query with filters
$sql = "
    SELECT a.application_id, a.student_id, a.status, a.submission_date, a.review_date,
           s.first_name, s.last_name, s.year_level, s.department,
           sch.title as scholarship_title,
           CONCAT(ad.first_name, ' ', ad.last_name) as reviewer_name
    FROM APPLICATIONS a
    LEFT JOIN STUDENTS s ON a.student_id = s.student_id
    LEFT JOIN SCHOLARSHIPS sch ON a.scholarship_id = sch.scholarship_id
    LEFT JOIN ADMINISTRATORS ad ON a.reviewed_by = ad.admin_id
    $where_clause
    $order_clause
";

// Execute the filtered query
if(!empty($params)) {
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $applications_query = mysqli_stmt_get_result($stmt);
} else {
    $applications_query = mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>

<html>
    <meta charset="UTF-8">
    <title>Application Management</title>
    <link rel="stylesheet" href="AppMgmt_Design.css">
<body>
    <section class="header">
    <img src="../icons/CSP_logo.png" alt="CSP Logo" class="CSP-logo">
    <h3>Centralized Scholarship Portal / Admin Navigation / Application Management</h3>
    </section>

    <h1>Application Management</h1>

  <section class="card-stats">
  <section class="stats-inner">
    <section class="card">
      <h2>Total Applications</h2>
      <p><?php echo $total_applications; ?></p>
</section>

    <section class="card">
      <h2>Pending Reviews</h2>
      <p><?php echo $pending_reviews; ?></p>
    </section>

    <section class="card">
      <h2>Approved</h2>
      <p><?php echo $approved_applications; ?></p>
    </section>
  </section>
</section>

<br>

<!-- Advanced Filtering -->

<section class="Adv-Filtr">
  <form method="GET" action="">
  <section class="filter-dropdown">
    <button class="Advanced-Filter">Advanced Filtering</button>
    <section class="dropdown-content">
      <section class="filter-group">
        <select name="status">
          <option value="all" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'selected' : ''; ?>>All status</option>
            <option value="Pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Under Review" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Under Review') ? 'selected' : ''; ?>>Under Review</option>
            <option value="Approved" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
            <option value="Rejected" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
        </select>
      </section>

      <section class="filter-group">
        <select name="year">
          <option value="all" <?php echo (!isset($_GET['year']) || $_GET['year'] == 'all') ? 'selected' : ''; ?>>All Year Levels</option>
            <option value="Freshman" <?php echo (isset($_GET['year']) && $_GET['year'] == 'Freshman') ? 'selected' : ''; ?>>Freshman</option>
            <option value="Sophomore" <?php echo (isset($_GET['year']) && $_GET['year'] == 'Sophomore') ? 'selected' : ''; ?>>Sophomore</option>
            <option value="Junior" <?php echo (isset($_GET['year']) && $_GET['year'] == 'Junior') ? 'selected' : ''; ?>>Junior</option>
            <option value="Senior" <?php echo (isset($_GET['year']) && $_GET['year'] == 'Senior') ? 'selected' : ''; ?>>Senior</option>
        </select>
      </section>

      <section class="filter-group">
        <select name="scholarship" id="filter_scholarship">
            <option value="all">All Scholarships</option>
                <?php foreach($scholarships_list as $scholarship): ?>
              <option value="<?php echo $scholarship['scholarship_id']; ?>" 
                <?php echo (isset($_GET['scholarship']) && $_GET['scholarship'] == $scholarship['scholarship_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($scholarship['title']); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </section>

      <section class="filter-group">
        <label>Sort by submission date</label>
        <section class="date-sorts">
          <button type="submit" name="sort" value="desc" class="sort-asc">Recent Submissions</button>
          <button type="submit" name="sort" value="asc" class="sort-desc">Oldest Submissions</button>
        </section>
      </section>

      <section class="dropdown-footer">
      <button type="submit" name="clear" value="1" class="clear-filters">Clear</button>
      <button type="submit" class="apply-filters">Apply</button>
      </section>
    </section>
  </section>

    <button type="submit" name="clear" value="1" class="Clear-Filter">Clear Filter/s</button>
  </form>
</section>

<!-- Applications Table -->

    <section class="table-container">
        <table>
        <tr>
            <th>Application ID </th>
            <th>Student ID </th>
            <th>Scholarship </th>
            <th>Current Status </th>
            <th>Submitted on </th>
            <th>Reviewed by </th>
            <th>Year Level </th>
        </tr>
    
<?php if($applications_query && mysqli_num_rows($applications_query) > 0): ?>
                <?php while($app = mysqli_fetch_assoc($applications_query)): ?>
                    <tr>
                        <td><?php echo $app['application_id']; ?></td>
                        <td><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['scholarship_title']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $app['status'])); ?>">
                                <?php echo $app['status']; ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($app['submission_date'])); ?></td>
                        <td><?php echo $app['reviewer_name'] ? htmlspecialchars($app['reviewer_name']) : 'Not reviewed'; ?></td>
                        <td><?php echo $app['year_level']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No applications found.</td>
                </tr>
            <?php endif; ?>

        </table>
    </section>
</section>

    <br>

    <section class="go-back">
        <button onclick="window.location.href='Server_Dashboard.php'">Go Back</button>
    </section>


    
</body>
</html>
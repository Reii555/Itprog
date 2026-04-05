<!-- 
 APPLICATION MANAGEMENT PAGE

 This page allows Super Admins, and other admins to manage scholarship applications.
 Features include:
    - Viewing all applications (Super Admin sees all, others see assigned/unassigned)
    - Filtering applications by status, year level, scholarship, and sorting by date
    - Changing application status with automatic reviewer assignment
    - Super Admin can re-assign applications to different admins
    - Viewing submitted documents in a modal without leaving the page
    
    Reii555
-->

<?php
session_start();
include("../../db_connect.php");

//check login
if(!isset($_SESSION['account_id'])){
    header("Location: ./login.php");
    exit();
}

//get admin ID and role FIRST
$admin_id = null;
$user_role = null;

$admin_query = mysqli_query($conn, "SELECT admin_id FROM administrators WHERE account_id = '" .
                mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'");
if($admin_query && mysqli_num_rows($admin_query) > 0){
    $admin_data = mysqli_fetch_assoc($admin_query);
    $admin_id = $admin_data['admin_id'];
}

// Get user role
$role_query = mysqli_query($conn, "SELECT role FROM ACCOUNTS WHERE account_id = '" . mysqli_real_escape_string($conn, $_SESSION['account_id']) . "'");
if($role_query) {
    $user_role = mysqli_fetch_assoc($role_query)['role'];
}

// Check if user is Super Admin (has full access)
$is_super_admin = ($user_role == 'Super Admin');
$is_it_admin = ($user_role == 'IT');

// ============================================
// AUTO-ASSIGN ADMIN WHEN VIEWING DOCUMENTS
// ============================================
if(isset($_GET['assign']) && isset($_GET['view_docs']) && $_GET['assign'] == '1') {
    $app_id = (int)$_GET['view_docs'];
    
    // Only assign if no reviewer is assigned yet
    $check_query = mysqli_query($conn, "SELECT reviewed_by FROM APPLICATIONS WHERE application_id = $app_id");
    if($check_query) {
        $check = mysqli_fetch_assoc($check_query);
        if(is_null($check['reviewed_by']) || $check['reviewed_by'] == '') {
            $assign_update = "UPDATE APPLICATIONS SET reviewed_by = $admin_id WHERE application_id = $app_id";
            mysqli_query($conn, $assign_update);
        }
    }
}

// ============================================
// HANDLE SINGLE STATUS UPDATE
// ============================================
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['app_id']) && isset($_POST['new_status'])) {
    $app_id = (int)$_POST['app_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    
    // Update the status with reviewer info
    $update = "UPDATE APPLICATIONS SET status = '$new_status', reviewed_by = $admin_id, review_date = NOW() WHERE application_id = $app_id";
    mysqli_query($conn, $update);
    
    // Refresh page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ============================================
// REASSIGN APPLICATION TO DIFFERENT ADMIN (Super Admin only)
// ============================================
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reassign_app_id']) && isset($_POST['new_reviewer_id'])) {
    // Only allow Super Admin to reassign
    if($is_super_admin) {
        $reassign_app_id = (int)$_POST['reassign_app_id'];
        $new_reviewer_id = (int)$_POST['new_reviewer_id'];
        
        if($new_reviewer_id > 0) {
            $reassign_update = "UPDATE APPLICATIONS SET reviewed_by = $new_reviewer_id WHERE application_id = $reassign_app_id";
            mysqli_query($conn, $reassign_update);
        }
        
        // Refresh page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// ============================================
// GET STATISTICS 
// ============================================

// Build access filter for stats based on user role
// Super Admin sees ALL, others see only assigned or unassigned
$access_filter = "";
if(!$is_super_admin) {
    $access_filter = "WHERE (reviewed_by IS NULL OR reviewed_by = $admin_id)";
}

// Total Applications
$total_apps_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM APPLICATIONS $access_filter");
$total_applications = ($total_apps_query) ? mysqli_fetch_assoc($total_apps_query)['total'] : 0;

// Pending Reviews
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM APPLICATIONS $access_filter " . ($access_filter ? "AND" : "WHERE") . " (status = 'Pending' OR status = 'Under Review')");
$pending_reviews = ($pending_query) ? mysqli_fetch_assoc($pending_query)['total'] : 0;

// Approved Applications
$approved_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM APPLICATIONS $access_filter " . ($access_filter ? "AND" : "WHERE") . " status = 'Approved'");
$approved_applications = ($approved_query) ? mysqli_fetch_assoc($approved_query)['total'] : 0;

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
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// ============================================
// BUILD QUERY 
// ============================================

$where_conditions = [];
$params = [];
$types = "";

// Restrict to assigned applications (Super Admin sees ALL)
if(!$is_super_admin) {
    $where_conditions[] = "(a.reviewed_by IS NULL OR a.reviewed_by = $admin_id)";
}

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
           CONCAT(ad.first_name, ' ', ad.last_name) as reviewer_name,
           a.reviewed_by
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

// Get all admins for Super Admin dropdown
$all_admins = [];
if($is_super_admin) {
    $admins_query = mysqli_query($conn, "SELECT ad.admin_id, ad.first_name, ad.last_name, a.role 
                                         FROM ADMINISTRATORS ad 
                                         JOIN ACCOUNTS a ON ad.account_id = a.account_id");
    while($admin = mysqli_fetch_assoc($admins_query)) {
        $all_admins[] = $admin;
    }
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
            <strong>Centralized Scholarship Portal</strong>
            <span> / <a href="Server_Dashboard.php">Dashboard</a> / Application Management</span>
    </section>

    <h1>Application Management</h1>

<!-- Modal for Documents -->
<?php
$show_modal = false;
$modal_application_id = null;
$modal_documents = [];
$modal_application_info = null;

if(isset($_GET['view_docs']) && !empty($_GET['view_docs'])) {
    $show_modal = true;
    $modal_application_id = (int)$_GET['view_docs'];
    
    // Fetch application info
    $info_query = "
        SELECT a.application_id, a.status, a.submission_date,
               s.first_name, s.last_name, s.student_id, s.department, s.year_level,
               sch.title as scholarship_title
        FROM APPLICATIONS a
        LEFT JOIN STUDENTS s ON a.student_id = s.student_id
        LEFT JOIN SCHOLARSHIPS sch ON a.scholarship_id = sch.scholarship_id
        WHERE a.application_id = ?
    ";
    $stmt = mysqli_prepare($conn, $info_query);
    mysqli_stmt_bind_param($stmt, "i", $modal_application_id);
    mysqli_stmt_execute($stmt);
    $info_result = mysqli_stmt_get_result($stmt);
    $modal_application_info = mysqli_fetch_assoc($info_result);
    
    // Fetch documents
    $doc_query = "SELECT document_id, file_name, file_type, docu_type FROM DOCUMENTS WHERE application_id = ?";
    $doc_stmt = mysqli_prepare($conn, $doc_query);
    mysqli_stmt_bind_param($doc_stmt, "i", $modal_application_id);
    mysqli_stmt_execute($doc_stmt);
    $doc_result = mysqli_stmt_get_result($doc_stmt);
    
    while($doc = mysqli_fetch_assoc($doc_result)) {
        $modal_documents[] = $doc;
    }
}
?>

<?php if($show_modal && $modal_application_info): ?>
<div id="documentModal" class="modal" style="display: flex;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Application Documents</h2>
            <a href="?<?php echo http_build_query(array_diff_key($_GET, ['view_docs' => ''])); ?>" class="close">&times;</a>
        </div>
        
        <div class="modal-body">
            <div class="modal-app-info">
                <div class="info-row">
                    <span class="info-label">Student:</span>
                    <span class="info-value"><?php echo htmlspecialchars($modal_application_info['first_name'] . ' ' . $modal_application_info['last_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Scholarship:</span>
                    <span class="info-value"><?php echo htmlspecialchars($modal_application_info['scholarship_title']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    <span class="info-value"><?php echo htmlspecialchars($modal_application_info['department']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Year Level:</span>
                    <span class="info-value"><?php echo $modal_application_info['year_level']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $modal_application_info['status'])); ?>">
                            <?php echo $modal_application_info['status']; ?>
                        </span>
                    </span>
                </div>
            </div>
            
            <div class="modal-documents">
                <h3>Submitted Documents</h3>
                <?php if(count($modal_documents) > 0): ?>
                    <?php foreach($modal_documents as $doc): ?>
                        <div class="modal-doc-item">
                            <div class="doc-info">
                                <div class="doc-title"><?php echo htmlspecialchars($doc['docu_type']); ?></div>
                                <div class="doc-meta"><?php echo htmlspecialchars($doc['file_name']); ?></div>
                            </div>
                            <a href="view_document_file.php?doc_id=<?php echo $doc['document_id']; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" 
                               class="view-doc-btn">
                                View
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-docs">No documents uploaded yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="modal-footer">
            <a href="?<?php echo http_build_query(array_diff_key($_GET, ['view_docs' => ''])); ?>" class="close-modal-btn">Close</a>
        </div>
    </div>
</div>
<?php endif; ?>

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
            <th>Application ID</th>
            <th>Student Name</th>
            <th>Scholarship</th>
            <th>Current Status</th>
            <th>Submitted on</th>
            <th>Reviewed by</th>
            <?php if($is_super_admin): ?>
                <th>Re-assign To</th>
            <?php endif; ?>
            <th>Year Level</th>
            <th>Actions</th>
        </tr>
    
<?php if($applications_query && mysqli_num_rows($applications_query) > 0): ?>
    <?php while($app = mysqli_fetch_assoc($applications_query)): ?>
        <tr>
            <td><?php echo $app['application_id']; ?></td>
            <td><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></td>
            <td><?php echo htmlspecialchars($app['scholarship_title']); ?></td>
            <td>
                <form method="POST" action="" style="display: inline;" onchange="this.submit()">
                    <input type="hidden" name="app_id" value="<?php echo $app['application_id']; ?>">
                    <select name="new_status" style="padding: 5px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #ccc;">
                        <option value="Pending" <?php echo $app['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Under Review" <?php echo $app['status'] == 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                        <option value="Approved" <?php echo $app['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="Rejected" <?php echo $app['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        <option value="Waitlisted" <?php echo $app['status'] == 'Waitlisted' ? 'selected' : ''; ?>>Waitlisted</option>
                    </select>
                </form>
            </td>
            <td><?php echo date('M d, Y', strtotime($app['submission_date'])); ?></td>
            <td><?php echo $app['reviewer_name'] ? htmlspecialchars($app['reviewer_name']) : 'Not assigned'; ?></td>
            <?php if($is_super_admin): ?>
                <td>
                    <form method="POST" action="" style="display: inline;" onchange="this.submit()">
                        <input type="hidden" name="reassign_app_id" value="<?php echo $app['application_id']; ?>">
                        <select name="new_reviewer_id" style="padding: 4px 6px; border-radius: 4px; font-size: 11px;">
                            <option value="">-- Assign --</option>
                            <?php foreach($all_admins as $admin_option): ?>
                                <option value="<?php echo $admin_option['admin_id']; ?>" 
                                    <?php echo ($app['reviewed_by'] == $admin_option['admin_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($admin_option['first_name'] . ' ' . $admin_option['last_name']); ?>
                                    (<?php echo $admin_option['role']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
            <?php endif; ?>
            <td><?php echo $app['year_level']; ?></td>
            <td>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['view_docs' => $app['application_id'], 'assign' => '1'])); ?>" class="view-docs-link">View Documents</a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
        <tr>
            <td colspan="<?php echo $is_super_admin ? '9' : '8'; ?>" style="text-align: center;">No applications found.</td>
        </tr>
<?php endif; ?>

    </table>
</section>

<br>

<section class="go-back">
    <button onclick="window.location.href='Server_Dashboard.php'">Go Back</button>
</section>

</body>
</html>
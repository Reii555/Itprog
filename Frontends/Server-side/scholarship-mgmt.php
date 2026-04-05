<?php
// Manages scholarship records with CRUD operations
// Features include: 
// - search 
// - pagination 
// summary statistics
session_start();
include("../../db_connect.php");

//check login
if(!isset($_SESSION['account_id'])){
    header("Location: ./login.php");
    exit();
}

//get admin ID
$admin_id = null;
$account_id = $_SESSION['account_id'];
$admin_query = "SELECT admin_id FROM administrators WHERE account_id = ?";
$stmt = mysqli_prepare($conn, $admin_query);
mysqli_stmt_bind_param($stmt, "i", $account_id);
mysqli_stmt_execute($stmt);
$admin_result = mysqli_stmt_get_result($stmt);

if($admin_result && mysqli_num_rows($admin_result) > 0){
    $admin_data = mysqli_fetch_assoc($admin_result);
    $admin_id = $admin_data['admin_id'];
} else {
    die("Error: Admin record not found for account ID: " . $account_id);
}
mysqli_stmt_close($stmt);

// ============================================
// ADD SCHOLARSHIP
// ============================================
if(isset($_POST['add'])){
    if($admin_id == null){
        $msg = "Error: Admin is not logged in.";
        $msg_type = "error";
    } else {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $eligibility = mysqli_real_escape_string($conn, $_POST['eligibility']);
        $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
        $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
        $release_status = mysqli_real_escape_string($conn, $_POST['release_status']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $stmt = mysqli_prepare($conn, "INSERT INTO scholarships (title, description, eligibility, requirements, deadline, release_status, status, created_by, created_at)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $eligibility, $requirements, $deadline, $release_status, $status, $admin_id);

        if(mysqli_stmt_execute($stmt)){
            $msg = "Scholarship added successfully.";
            $msg_type = "success";
        } else {
            $msg = "Error: " . mysqli_error($conn);
            $msg_type = "error";
        }
        mysqli_stmt_close($stmt);
    }
}

// ============================================
// EDIT SCHOLARSHIP
// ============================================
if(isset($_POST['edit'])){
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $eligibility = mysqli_real_escape_string($conn, $_POST['eligibility']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $release_status = mysqli_real_escape_string($conn, $_POST['release_status']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $stmt = mysqli_prepare($conn, "UPDATE scholarships SET title=?, description=?, eligibility=?, requirements=?, deadline=?, release_status=?, status=? WHERE scholarship_id=?");
    mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $eligibility, $requirements, $deadline, $release_status, $status, $id);

    if(mysqli_stmt_execute($stmt)){
        $msg = "Scholarship updated successfully.";
        $msg_type = "success";
    } else {
        $msg = "Error: " . mysqli_error($conn);
        $msg_type = "error";
    }
    mysqli_stmt_close($stmt);
}

// ============================================
// DELETE SCHOLARSHIP
// ============================================
if(isset($_POST['delete'])){
    $id = (int)$_POST['id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM scholarships WHERE scholarship_id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if(mysqli_stmt_execute($stmt)){
        $msg = "Scholarship deleted successfully.";
        $msg_type = "success";
    } else {
        $msg = "Error: " . mysqli_error($conn);
        $msg_type = "error";
    }
    mysqli_stmt_close($stmt);
}

// ============================================
// SEARCH AND PAGINATION USING GET METHOD
// ============================================
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$where = "";
if($search != ""){
    $where = "WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}

//get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM scholarships $where";
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total_items = $total_row['total'];
$total_pages = ($total_items > 0) ? ceil($total_items / $limit) : 1;

// ============================================
// GET SCHOLARSHIPS WITH CREATOR NAME (w/ LEFT JOIN)
// ============================================
$query = "SELECT s.*, CONCAT(a.first_name, ' ', a.last_name) as creator
        FROM scholarships s
        LEFT JOIN administrators a ON s.created_by = a.admin_id
        $where
        ORDER BY s.scholarship_id DESC
        LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);

// ============================================
// STATISTICS FOR SUMMARY CARDS
// ============================================
$total_result = mysqli_query($conn, "SELECT COUNT(*) as c FROM scholarships");
$total = mysqli_fetch_assoc($total_result)['c'];

$published_result = mysqli_query($conn, "SELECT COUNT(*) as c FROM scholarships WHERE release_status = 'Published'");
$published = mysqli_fetch_assoc($published_result)['c'];

$ongoing_result = mysqli_query($conn, "SELECT COUNT(*) as c FROM scholarships WHERE status = 'Ongoing'");
$ongoing = mysqli_fetch_assoc($ongoing_result)['c'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Manage Scholarships</title>
        <link rel="stylesheet" href="ScholMgmt_Design.css">
    </head>

    <body>
        <section class="header">
            <img src="../icons/CSP_logo.png" alt="CSP Logo" class="CSP-logo">
            <strong>Centralized Scholarship Portal</strong>
            <span> / <a href="Server_Dashboard.php">Dashboard</a> / Manage Scholarships</span>
        </section>

        <section class="page-title">
            <h2>Scholarship Management</h2>
        </section>

        <section class="summary">
            <h3 class="visually-hidden">Scholarship Statistics</h3>
            <div class="stat-card">
                <h3>Total Scholarships</h3>
                <p><?php echo $total; ?></p>
            </div>

            <div class="stat-card">
                <h3>Published</h3>
                <p><?php echo $published; ?></p>
            </div>

            <div class="stat-card">
                <h3>Total Ongoing</h3>
                <p><?php echo $ongoing; ?></p>
            </div>
        </section>

        <!-- Messages Section -->
        <?php if(isset($msg)): ?>
            <section class="message message-<?php echo $msg_type; ?>">
                <p><?php echo $msg; ?></p>
            </section>
        <?php endif; ?>

        <!-- Control Panel Section -->
         <section class="control-panel">
            <h3 class="visually-hidden">Search and Actions</h3>
            <form method="GET" class="searchbox">
                <input type="search" name="search" placeholder="Search Scholarship..." value="<?php echo htmlspecialchars($search); ?>">
            </form>

            <div class="action-button">
                <button type="button" class="butt-add" id="addButt" onclick="openAction('add')">+ Add Scholarship</button>
                <button type="button" class="butt-edit" id="editButt" onclick="openAction('edit')" disabled>Edit</button>
                <button type="button" class="butt-del" id="delButt" onclick="openAction('delete')" disabled>Delete</button>
            </div>
         </section>

         <!--Scholarships Table Section -->
         <section class="scholarships-table">
            <h3 class="visually-hidden">Scholarships List</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Scholarship ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Eligibility</th>
                            <th>Requirements</th>
                            <th>Deadline</th>
                            <th>Release Status</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr onclick="selectRow(this)"
                                    data-id="<?php echo $row['scholarship_id']; ?>"
                                    data-title="<?php echo $row['title']; ?>"
                                    data-description="<?php echo $row['description']; ?>"
                                    data-eligibility="<?php echo htmlspecialchars($row['eligibility']); ?>"
                                    data-requirements="<?php echo htmlspecialchars($row['requirements']); ?>"
                                    data-deadline="<?php echo date('Y-m-d\TH:i', strtotime($row['deadline'])); ?>"
                                    data-release="<?php echo $row['release_status']; ?>"
                                    data-status="<?php echo $row['status']; ?>">
                                    <td><?php echo $row['scholarship_id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                    <td>
                                        <?php 
                                            echo htmlspecialchars(substr($row['description'], 0, 50));
                                            if(strlen($row['description']) > 50) { echo '...'; }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo htmlspecialchars(substr($row['eligibility'], 0, 50));
                                            if(strlen($row['eligibility']) > 50) { echo '...'; }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo htmlspecialchars(substr($row['requirements'], 0, 50));
                                            if(strlen($row['requirements']) > 50) { echo '...'; }
                                        ?>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($row['deadline'])); ?></td>
                                    <td><span class="badge badge-<?php echo strtolower($row['release_status']); ?>"><?php echo $row['release_status']; ?></span></td>
                                    <td><span class="badge badge-<?php echo strtolower($row['status']); ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                    <td><?php echo htmlspecialchars($row['creator'] ?? 'Admin'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="8">No scholarships found. To create one, click "Add Scholarship".</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- pagination -->
             <div class="pagination">
                <div class="pagination-info">
                    Showing <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $total_items); ?> out of  <?php echo $total_items; ?> items
                </div>
                <div class="pagination-controls">
                    <?php if($page > 1): ?>
                        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&limit=<?php echo $limit; ?>">‹ Prev</a>
                    <?php else: ?>
                        <button disabled>‹ Prev</button>
                    <?php endif; ?>

                    <span>Page <?php echo $page; ?> of <?php echo max(1, $total_pages); ?></span>

                    <?php if($page < $total_pages): ?>
                        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&limit=<?php echo $limit; ?>">Next ›</a>
                    <?php else: ?>
                        <button disabled>Next ›</button>
                    <?php endif; ?>

                    <form method="GET" class="limit-form">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="page" value="1">
                        <select name="limit" onchange="this.form.submit()">
                            <option value="5" <?php echo $limit == 5 ? 'selected' : ''; ?>>5 per page</option>
                            <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10 per page</option>
                            <option value="25" <?php echo $limit == 25 ? 'selected' : ''; ?>>25 per page</option>
                            <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50 per page</option>
                        </select>
                    </form>
                </div>
            </div>
        </section>

        <!--add scholarship function-->
        <div class="function" id="addScho">
            <div class="function-content">
                <div class="function-header">
                    <h3>Add Scholarship</h3>
                    <span class="close" onclick="closeAction('addScho')">&times;</span>
                </div>
                <form method="POST">
                    <div class="function-body">
                        <div class="form-group">
                            <label>Scholarship Title *</label>
                            <input type="text" name="title" placeholder="Scholarship Title" required>
                        </div>
                        <div class="form-group">
                            <label>Description *</label>
                            <textarea name="description" placeholder="Add description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Eligibility *</label>
                            <textarea name="eligibility" placeholder="Who can apply" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Requirements *</label>
                            <textarea name="requirements" placeholder="Required documents" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Deadline *</label>
                            <input type="datetime-local" name="deadline" required>
                        </div>
                        <div class="form-group">
                            <label>Release Status </label>
                            <select name="release_status">
                                <option value="Published">Published</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Scholarship Status </label>
                            <select name="status">
                                <option value="Completed">Completed</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Upcoming">Upcoming</option>
                            </select>
                        </div>
                    </div>
                    <div class="function-footer">
                        <button type="button" onclick="closeAction('addScho')">Cancel</button>
                        <button type="submit" name="add">Add Scholarship</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- edit scholarship function -->
         <div class="function" id="editScho">
            <div class="function-content">
                <div class="function-header">
                    <h3>Edit Scholarship</h3>
                    <span class="close" onclick="closeAction('editScho')">&times;</span>
                </div>
                <form method="POST">
                    <div class="function-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Scholarship Title *</label>
                            <input type="text" name="title" id="edit_title" placeholder="Scholarship Title" required>
                        </div>
                        <div class="form-group">
                            <Label>Description *</label>
                            <textarea name="description" id="edit_description" placeholder="Description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Eligibility *</label>
                            <textarea name="eligibility" id="edit_eligibility" placeholder="Who can apply" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Requirements *</label>
                            <textarea name="requirements" id="edit_requirements" placeholder="Required documents" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Deadline *</label>
                            <input type="datetime-local" name="deadline" id="edit_deadline" required>
                        </div>
                        <div class="form-group">
                            <label>Release Status </label>
                            <select name="release_status" id="edit_release">
                                <option value="Published">Published</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Scholarship Status </label>
                            <select name="status" id="edit_status">
                                <option value="Completed">Completed</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Upcoming">Upcoming</option>
                            </select>
                        </div>
                    </div>
                    <div class="function-footer">
                        <button type="button" onclick="closeAction('editScho')">Cancel</button>
                        <button type="submit" name="edit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

         <!-- delete selected scholarship section -->
          <div class="function" id="deleteScho">
            <div class="function-content">
                <div class="function-header">
                    <h3>Confirm Delete</h3>
                    <span class="close" onclick="closeAction('deleteScho')">&times;</span>
                </div>
                <form method="POST">
                    <div class="function-body">
                        <input type="hidden" name="id" id="delete_id">
                        <p>Are you sure you want to delete <strong id="delete_title"></strong>?</p>
                        <p class="warning-text">This action cannot be undone.</p>
                    </div>
                    <div class="function-footer">
                        <button type="button" onclick="closeAction('deleteScho')">Cancel</button>
                        <button type="submit" name="delete">Delete</button>
                    </div>
                </form>
            </div>
        </div>

          <script>
            // script is for frontend interactions like selecting a row 
            // and opening the add/edit/delete actions with pre-filled data for edit and delete
            let selectedRow = null;

            //select row function, for edit and delete scholarship function
            function selectRow(row){
                //removes previous selection
                document.querySelectorAll('tbody tr').forEach(r => {
                    r.classList.remove('selected');
                });

                //add selected class to clicked row
                row.classList.add('selected');
                selectedRow = row;

                //enables edit and delete buttons after selecting a row
                document.getElementById('editButt').disabled = false;
                document.getElementById('delButt').disabled = false;
            }

            //function to open the selected function (add/edit/delete)
            function openAction(type){
                if(type === 'edit' && !selectedRow){
                    alert('Please select a scholarship first');
                    return;
                }
                if(type === 'delete' && !selectedRow){
                    alert('Please select a scholarship first');
                    return;
                }

                if(type === 'add'){
                    document.getElementById('addScho').classList.add('show');
                } else if(type === 'edit') {
                    if(selectedRow && selectedRow.dataset){
                        document.getElementById('edit_id').value = selectedRow.dataset.id;
                        document.getElementById('edit_title').value = selectedRow.dataset.title;
                        document.getElementById('edit_description').value = selectedRow.dataset.description;
                        document.getElementById('edit_eligibility').value = selectedRow.dataset.eligibility;
                        document.getElementById('edit_requirements').value = selectedRow.dataset.requirements;
                        document.getElementById('edit_deadline').value = selectedRow.dataset.deadline;
                        document.getElementById('edit_release').value = selectedRow.dataset.release;
                        document.getElementById('edit_status').value = selectedRow.dataset.status;
                        document.getElementById('editScho').classList.add('show');
                    }
                } else if(type === 'delete'){
                    if(selectedRow && selectedRow.dataset){
                        document.getElementById('delete_id').value = selectedRow.dataset.id;
                        document.getElementById('delete_title').innerText = selectedRow.dataset.title;
                        document.getElementById('deleteScho').classList.add('show');
                    }
                }
            }

            //closing the functions
            function closeAction(modalId) {
                document.getElementById(modalId).classList.remove('show');
            }

            //close modal when clicking outside the action area
            window.onclick = function(e){
                if(e.target.classList.contains('function')){
                    e.target.classList.remove('show');
                }
            }
          </script>
    </body>
</html>
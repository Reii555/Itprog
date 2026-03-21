<?php
// db_connect.php
// Database connection file with testing data
// @alledelweiss

// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'scholarship_db'; // Make sure this matches your database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Disable foreign key checks temporarily to allow inserts in correct order
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// ============================================
// TESTING DATA - Insert sample data if tables are empty
// ============================================

// First, insert administrators (since scholarships reference them)
$check_admins = mysqli_query($conn, "SELECT COUNT(*) as count FROM ADMINISTRATORS");
if ($check_admins) {
    $admins_count = mysqli_fetch_assoc($check_admins)['count'];

    if ($admins_count == 0) {
        // Insert admin account first
        mysqli_query($conn, "INSERT INTO ACCOUNTS (email, password, role, is_active, account_created, password_upd, last_login) 
                            VALUES ('admin@csp.edu', 'admin123', 'Admin', 1, NOW(), NOW(), NOW())");
        $admin_account_id = mysqli_insert_id($conn);
        
        // Insert IT account
        mysqli_query($conn, "INSERT INTO ACCOUNTS (email, password, role, is_active, account_created, password_upd, last_login) 
                            VALUES ('it.admin@csp.edu', 'it123', 'IT', 1, NOW(), NOW(), NOW())");
        $it_account_id = mysqli_insert_id($conn);
        
        // Insert administrators
        $admin_data = [
            ['Super Admin', 'User', $admin_account_id, 912345678],
            ['IT', 'Administrator', $it_account_id, 923456789]
        ];
        
        foreach ($admin_data as $admin) {
            $insert = "INSERT INTO ADMINISTRATORS (first_name, last_name, account_id, contact_num) 
                       VALUES ('{$admin[0]}', '{$admin[1]}', {$admin[2]}, {$admin[3]})";
            mysqli_query($conn, $insert);
        }
        echo "<!-- Sample administrators inserted -->\n";
    }
}

// Check and insert sample accounts
$check_accounts = mysqli_query($conn, "SELECT COUNT(*) as count FROM ACCOUNTS");
if ($check_accounts) {
    $accounts_count = mysqli_fetch_assoc($check_accounts)['count'];

    if ($accounts_count < 4) { // Only insert if we have less than 4 accounts
        // Insert sample student accounts
        $accounts_data = [
            ['john.doe@email.com', 'password123', 'Student', 1],
            ['jane.smith@email.com', 'password123', 'Student', 1],
            ['bob.wilson@email.com', 'password123', 'Student', 1],
            ['alice.brown@email.com', 'password123', 'Student', 1]
        ];
        
        foreach ($accounts_data as $account) {
            $insert = "INSERT INTO ACCOUNTS (email, password, role, is_active, account_created, password_upd, last_login) 
                       VALUES ('{$account[0]}', '{$account[1]}', '{$account[2]}', {$account[3]}, NOW(), NOW(), NOW())";
            mysqli_query($conn, $insert);
        }
        echo "<!-- Sample accounts inserted -->\n";
    }
}

// Check and insert sample students
$check_students = mysqli_query($conn, "SELECT COUNT(*) as count FROM STUDENTS");
if ($check_students) {
    $students_count = mysqli_fetch_assoc($check_students)['count'];

    if ($students_count == 0) {
        // Get account IDs for students
        $accounts = mysqli_query($conn, "SELECT account_id FROM ACCOUNTS WHERE role='Student'");
        $student_accounts = [];
        while ($acc = mysqli_fetch_assoc($accounts)) {
            $student_accounts[] = $acc['account_id'];
        }
        
        // Insert sample students
        $students_data = [
            ['John', 'Doe', 'Computer Science', 'Freshman', 912345678],
            ['Jane', 'Smith', 'Business Administration', 'Sophomore', 923456789],
            ['Bob', 'Wilson', 'Engineering', 'Junior', 934567890],
            ['Alice', 'Brown', 'Education', 'Senior', 945678901]
        ];
        
        foreach ($students_data as $index => $student) {
            if (isset($student_accounts[$index])) {
                $insert = "INSERT INTO STUDENTS (account_id, first_name, last_name, department, year_level, contact_num) 
                           VALUES ({$student_accounts[$index]}, '{$student[0]}', '{$student[1]}', '{$student[2]}', '{$student[3]}', {$student[4]})";
                mysqli_query($conn, $insert);
            }
        }
        echo "<!-- Sample students inserted -->\n";
    }
}

// Check and insert sample scholarships
$check_scholarships = mysqli_query($conn, "SELECT COUNT(*) as count FROM SCHOLARSHIPS");
if ($check_scholarships) {
    $scholarships_count = mysqli_fetch_assoc($check_scholarships)['count'];

    if ($scholarships_count == 0) {
        // Get admin ID from ADMINISTRATORS table
        $admin_query = mysqli_query($conn, "SELECT admin_id FROM ADMINISTRATORS LIMIT 1");
        if (mysqli_num_rows($admin_query) > 0) {
            $admin_data = mysqli_fetch_assoc($admin_query);
            $admin_id = $admin_data['admin_id'];
            
            // Insert sample scholarships with valid admin_id
            $scholarships_data = [
                ['Academic Excellence Scholarship', 'For students with outstanding academic performance. Maintain a GWA of 1.5 or higher.', '2026-06-30 23:59:59', 'Published', 'Ongoing', $admin_id],
                ['Sports Scholarship', 'For student-athletes representing the university in various sports events.', '2026-07-15 23:59:59', 'Published', 'Ongoing', $admin_id],
                ['Financial Aid Scholarship', 'For students with demonstrated financial need and good academic standing.', '2026-05-30 23:59:59', 'Published', 'Ongoing', $admin_id],
                ['STEM Excellence Award', 'For students excelling in Science, Technology, Engineering, and Mathematics fields.', '2026-08-15 23:59:59', 'Published', 'Upcoming', $admin_id],
                ['Leadership Scholarship', 'For student leaders who have shown exceptional leadership skills.', '2026-09-01 23:59:59', 'Published', 'Upcoming', $admin_id],
                ['Merit Scholarship', 'Based on academic achievement and extracurricular involvement.', '2026-04-30 23:59:59', 'Draft', 'Upcoming', $admin_id]
            ];
            
            foreach ($scholarships_data as $scholar) {
                $insert = "INSERT INTO SCHOLARSHIPS (title, description, deadline, release_status, status, created_by, created_at) 
                           VALUES ('{$scholar[0]}', '{$scholar[1]}', '{$scholar[2]}', '{$scholar[3]}', '{$scholar[4]}', {$scholar[5]}, NOW())";
                mysqli_query($conn, $insert);
            }
            echo "<!-- Sample scholarships inserted -->\n";
        }
    }
}

// Check and insert sample applications
$check_applications = mysqli_query($conn, "SELECT COUNT(*) as count FROM APPLICATIONS");
if ($check_applications) {
    $applications_count = mysqli_fetch_assoc($check_applications)['count'];

    if ($applications_count == 0) {
        // Get student IDs
        $students = mysqli_query($conn, "SELECT student_id FROM STUDENTS");
        $student_ids = [];
        while ($stu = mysqli_fetch_assoc($students)) {
            $student_ids[] = $stu['student_id'];
        }
        
        // Get scholarship IDs
        $scholarships = mysqli_query($conn, "SELECT scholarship_id FROM SCHOLARSHIPS WHERE status='Ongoing'");
        $scholarship_ids = [];
        while ($sch = mysqli_fetch_assoc($scholarships)) {
            $scholarship_ids[] = $sch['scholarship_id'];
        }
        
        // Insert sample applications
        if (!empty($student_ids) && !empty($scholarship_ids)) {
            $dates = ['2026-01-15 10:30:00', '2026-01-20 14:45:00', '2026-01-25 09:15:00', '2026-02-01 16:20:00'];
            
            // John Doe's applications
            if (isset($student_ids[0]) && isset($scholarship_ids[0])) {
                $insert1 = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                           VALUES ({$student_ids[0]}, {$scholarship_ids[0]}, 'Submitted', '{$dates[0]}')";
                mysqli_query($conn, $insert1);
            }
            
            if (isset($student_ids[0]) && isset($scholarship_ids[1])) {
                $insert2 = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                           VALUES ({$student_ids[0]}, {$scholarship_ids[1]}, 'Under Review', '{$dates[1]}')";
                mysqli_query($conn, $insert2);
            }
            
            // Jane Smith's applications
            if (isset($student_ids[1]) && isset($scholarship_ids[0])) {
                $insert3 = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                           VALUES ({$student_ids[1]}, {$scholarship_ids[0]}, 'Approved', '{$dates[2]}')";
                mysqli_query($conn, $insert3);
            }
            
            if (isset($student_ids[1]) && isset($scholarship_ids[1])) {
                $insert4 = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                           VALUES ({$student_ids[1]}, {$scholarship_ids[1]}, 'Draft', NOW())";
                mysqli_query($conn, $insert4);
            }
            
            // Bob Wilson's applications
            if (isset($student_ids[2]) && isset($scholarship_ids[1])) {
                $insert5 = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                           VALUES ({$student_ids[2]}, {$scholarship_ids[1]}, 'Rejected', '{$dates[3]}')";
                mysqli_query($conn, $insert5);
            }
            
            // Alice Brown's applications
            if (isset($student_ids[3]) && isset($scholarship_ids[0])) {
                $insert6 = "INSERT INTO APPLICATIONS (student_id, scholarship_id, status, submission_date) 
                           VALUES ({$student_ids[3]}, {$scholarship_ids[0]}, 'Waitlisted', '{$dates[1]}')";
                mysqli_query($conn, $insert6);
            }
        }
        echo "<!-- Sample applications inserted -->\n";
    }
}

// Check and insert sample documents
$check_documents = mysqli_query($conn, "SELECT COUNT(*) as count FROM DOCUMENTS");
if ($check_documents) {
    $documents_count = mysqli_fetch_assoc($check_documents)['count'];

    if ($documents_count == 0) {
        // Get application IDs
        $applications = mysqli_query($conn, "SELECT application_id FROM APPLICATIONS WHERE status != 'Draft' LIMIT 5");
        $app_ids = [];
        while ($app = mysqli_fetch_assoc($applications)) {
            $app_ids[] = $app['application_id'];
        }
        
        // Insert sample documents
        $doc_types = ['Certificate of Enrollment', 'Grades', 'Recommendation Letter', 'Valid ID', '2x2 Picture'];
        $file_names = ['enrollment.pdf', 'grades.pdf', 'recommendation.pdf', 'id.jpg', 'picture.jpg'];
        $file_types = ['application/pdf', 'application/pdf', 'application/pdf', 'image/jpeg', 'image/jpeg'];
        
        foreach ($app_ids as $index => $app_id) {
            $doc_index = $index % count($doc_types);
            $insert = "INSERT INTO DOCUMENTS (application_id, file_name, file_type, docu_type) 
                       VALUES ({$app_id}, '{$file_names[$doc_index]}', '{$file_types[$doc_index]}', '{$doc_types[$doc_index]}')";
            mysqli_query($conn, $insert);
        }
        echo "<!-- Sample documents inserted -->\n";
    }
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// ============================================
// TESTING ACCESS - Quick login for testing
// ============================================

// For testing purposes, if no student is logged in, automatically log in the first student
if (!isset($_SESSION['student_id'])) {
    $first_student = mysqli_query($conn, "SELECT student_id FROM STUDENTS LIMIT 1");
    if ($first_student && mysqli_num_rows($first_student) > 0) {
        $student = mysqli_fetch_assoc($first_student);
        $_SESSION['student_id'] = $student['student_id'];
        
        // Get student name for display
        $name_query = mysqli_query($conn, "SELECT first_name, last_name FROM STUDENTS WHERE student_id = {$student['student_id']}");
        if ($name_query && mysqli_num_rows($name_query) > 0) {
            $name_data = mysqli_fetch_assoc($name_query);
            $_SESSION['user_name'] = $name_data['first_name'] . ' ' . $name_data['last_name'];
        }
    }
}

// ============================================
// QUICK LOGIN HANDLER for testing
// ============================================

if (isset($_GET['login'])) {
    $student_id = (int)$_GET['login'];
    
    // Verify student exists
    $check_student = mysqli_query($conn, "SELECT student_id FROM STUDENTS WHERE student_id = $student_id");
    if ($check_student && mysqli_num_rows($check_student) > 0) {
        $_SESSION['student_id'] = $student_id;
        
        // Get student name
        $name_query = mysqli_query($conn, "SELECT first_name, last_name FROM STUDENTS WHERE student_id = $student_id");
        if ($name_query && mysqli_num_rows($name_query) > 0) {
            $name_data = mysqli_fetch_assoc($name_query);
            $_SESSION['user_name'] = $name_data['first_name'] . ' ' . $name_data['last_name'];
        }
        
        header('Location: home.php');
        exit();
    }
}

// ============================================
// HELPER FUNCTIONS FOR TESTING
// ============================================

/**
 * Get all test users for debugging
 */
function getTestUsers($conn) {
    $query = "SELECT s.student_id, s.first_name, s.last_name, s.department, s.year_level, 
                     a.email, a.password, a.role
              FROM STUDENTS s
              JOIN ACCOUNTS a ON s.account_id = a.account_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

/**
 * Display test user info in footer (for debugging)
 */
function showTestUserInfo($conn) {
    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
        $query = "SELECT s.first_name, s.last_name, a.email 
                  FROM STUDENTS s
                  JOIN ACCOUNTS a ON s.account_id = a.account_id
                  WHERE s.student_id = $student_id";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            echo "<!-- Logged in as: {$user['first_name']} {$user['last_name']} ({$user['email']}) -->";
        }
    }
}

// Call the function to show current user in HTML comments
showTestUserInfo($conn);
?>

<?php
// CLIENT-SIDE
// Helper php for creating first-time applications or redirecting to existing applications.

session_start();
require("../../db_connect.php");

if (!isset($_SESSION['student_acc_id']) || !isset($_GET['scholarship_id'])) {
    header("Location: home.php");
    exit();
}

$account_id = $_SESSION['student_acc_id'];
$scholarship_id = (int)$_GET['scholarship_id'];

// get student ID
$studRes = mysqli_query($conn, "SELECT student_id FROM STUDENTS WHERE account_id='$account_id'");
$student_id = mysqli_fetch_assoc($studRes)['student_id'];

// check if application exists
$appRes = mysqli_query($conn, "SELECT application_id FROM APPLICATIONS WHERE student_id=$student_id AND scholarship_id=$scholarship_id");
if (mysqli_num_rows($appRes) > 0) {
    $application_id = mysqli_fetch_assoc($appRes)['application_id'];
} else {
    // create new draft
    mysqli_query($conn, "INSERT INTO APPLICATIONS (student_id, scholarship_id, status) VALUES ($student_id, $scholarship_id, 'Draft')");
    $application_id = mysqli_insert_id($conn);
}

// redirect to application form
header("Location: application-form.php?id=$application_id");
exit();
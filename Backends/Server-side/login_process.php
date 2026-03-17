<?php
include("db_connect.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $accountID = $_POST['accountID'];
    $password = $_POST['password'];

    $query = "SELECT * FROM ACCOUNTS WHERE accountID='$accountID' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['accountID'] = $user['accountID'];
        $_SESSION['role'] = $user['role'];
    } else {
        $_SESSION['error'] = "Invalid accound ID or password.";
        header("Location: ../frontend/login.php");
        exit();
    }

}

?>
<?php 
// CLIENT-SIDE LOGIN PAGE 
// Displays the login form and handles user authentication for student accounts in the CSP system.
// @isabel cubs

session_start();
include("../../db_connect.php");

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate account
    $query = "SELECT * FROM accounts WHERE email='$email' AND role='student'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // validate password
        if($password == $row['password']) {
            $_SESSION['account_id'] = $row['account_id'];
            $_SESSION['role'] = $row['role'];

            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Account not found');</script>";
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Student Login - Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client_login.css">
    </head>
    <body>
        <header>
            <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
        </header>
        <section id="login">
            <h3>Welcome Student!</h3>

            <form method="POST" action="">
                <p>Email</p>
                <input type="text" name="email" required>
                <p>Password</p>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="login" value="Login" />
             </form>
            <p id="note">Forgot password? Submit a ticket <a href="IT_support.php">here.</a></p>
        </section>
    </body>
</html>
<?php
session_start();
include("../../db_connect.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM ACCOUNTS WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        $_SESSION['account_id'] = $user['account_id'];
        $_SESSION['role'] = $user['role'];

        header("Location: Server_Dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login to Dashboard</title>
    </head>
    <body>
        <header>
            <div>
                <img src="logo.png" alt="Portal Logo" width="80" height="80">
                <h1>Centralized Scholarship Portal</h1>
            </div>
        </header>

        <main>
            <h2>Login to Dashboard</h2>
            <form method="post">
                <label for="email">Email</label><br>
                <input type="text" id="email" name="email" required>
                <br><br>

                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" required>
                <br><br>

                <button type="submit">Login</button>
            </form>

            <p>Forgot password? Submit a ticket <a href="test.html">here.</a></p>

            <?php
                if(isset($_SESSION['error'])){
                    echo "<p>". $error ."</p>";
                }
            ?>
        </main>
    </body>
</html>

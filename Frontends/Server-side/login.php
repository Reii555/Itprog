<?php
session_start();
include("../../db_connect.php");

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    //input validation
    if(empty($email) || empty($password)){
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email format.";
    } else {
        //proceed to login check 
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
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login to Dashboard</title>
    </head>
    <script>
        //for live input validations (frontend)
        function validateEmail(){
            let email = document.getElementById("email").value;
            let error = document.getElementById("emailError");
            let pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

            if (email === ""){
                error.innerHTML = "Email is required.";
                return false;
            } else if (!pattern.test(email)){
                error.innerHTML = "Invalid email format.";
                return false;
            } else {
                error.innerHTML = "";
                return true;
            }
        }

        function validatePassword(){
            let password = document.getElementById("password").value;
            let error = document.getElementById("passwordError");
            
            if(password === ""){
                error.innerHTML = "Password is required.";
                return false;
            } else {
                error.innerHTML = "";
                return true;
            }
        }

        function validateForm() {
            let emailValid = validateEmail();
            let passValid = validatePassword();

            return emailValid && passValid;
        }

    </script>
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
                <input type="text" id="email" name="email" value="<?php if(isset($email)) echo $email; ?>" 
                    onkeyup="validateEmail()" required>
                <br>
                <span id="emailError" style="color:red;"></span>
                <br><br>

                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" 
                    onkeyup="validatePassword()" required>
                <br>
                <span id="passwordError" style="color:red;"></span>
                <br><br>

                <button type="submit">Login</button>
            </form>

            <p>Forgot password? Submit a ticket <a href="test.html">here.</a></p>

            <?php
                if(!empty($error)){
                    echo "<p style='color:red;'>$error</p>";
                }
            ?>
        </main>
    </body>
</html>

<?php
session_start();
include("../../db_connect.php");

//$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM accounts WHERE email='$email' AND (role='admin' OR role='IT')";
    $result = mysqli_query($conn, $query);

    //input validation
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        //validate password
        if($password == $row['password']) {
            $update_query = "UPDATE accounts SET last_login=NOW() WHERE account_id= " . $row['account_id'];
            mysqli_query($conn, $update_query);

            $_SESSION['account_id'] = $row['account_id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $row['email'];

            header("Location: Server_Dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Account not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login to Dashboard</title>
    <link rel="stylesheet" href="adm-login_styles.css">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-wrapper">
                <img src="../icons/CSP_logo.png" alt="CSP Logo" class="logo">
            </div>
            <h1>Centralized Scholarship Portal</h1>
        </div>
        <div class="login-content">
            <h2>Login to Dashboard</h2>

            <form method="POST" class="login-form" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="<?php if(isset($email)) echo htmlspecialchars($email); ?>" onkeyup="validateEmail()" required>
                    <span id="emailError" class="error-message"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" onkeyup="validatePassword()" required>
                    <span id="passwordError" class="error-message"></span>
                </div>

                <button type="submit" class="login-butt">Login</button>
            </form>

            <div class="forgot-password">
                <p>Forgot password? Submit a ticket <a href="IT_Support.php">here.</a></p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="server-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        //for live input validations (frontend)
        function validateEmail() {
            let email = document.getElementById("email").value;
            let error = document.getElementById("emailError");
            let pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

            if (email === "") {
                error.innerHTML = "Email is required.";
                return false;
            } else if (!pattern.test(email)) {
                error.innerHTML = "Invalid email format.";
                return false;
            } else {
                error.innerHTML = "";
                return true;
            }
        }

        function validatePassword() {
            let password = document.getElementById("password").value;
            let error = document.getElementById("passwordError");

            if (password === "") {
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
</body>
</html>
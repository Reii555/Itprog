<?php
session_start();
include("../../db_connect.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //input validation
    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        //proceed to login check 
        $query = "SELECT * FROM ACCOUNTS WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
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
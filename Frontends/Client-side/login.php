<?php 
// CLIENT-SIDE LOGIN PAGE 
// Displays the login form and handles user authentication for student accounts in the CSP system.
// @isabel cubs
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program Login</title>
        <link rel="stylesheet" href="client_login.css">
    </head>
    <body>
        <header>
            <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
        </header>
        <section id="login">
            <h3>Welcome Student!</h3>

            <p>Username</p>
            <input type="text" name="username" required>
            <p>Password</p>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Login" />
            <p id="note">Forgot Password? Contact your IT Support.</p>
        </section>
    </body>
</html>
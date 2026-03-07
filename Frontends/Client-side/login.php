<?php 
// CLIENT-SIDE LOGIN PAGE
// Displays the login form and handles user authentication for student accounts in the CSP system.
// @isabel cubs
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client.css">
    </head>
    <body>
        <h1>[X] Centralized Scholarship Portal</h1>
        <section>
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
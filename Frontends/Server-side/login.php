<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Centralized Scholarship Portal</title>
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
            <form action="../backend/login_process.php" method="post">
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
                    echo "<p>" . $_SESSION['error'] . "</p>";
                    unset($_SESSION['error']);
                }
            ?>
        </main>
    </body>
</html>

<!--

LOGOUT 

This file simply destroys the user's session and redirects 
them to the login page. It is used when the user clicks the 
"Logout" option in the settings dropdown on the dashboard.

    Reii555

-->

<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
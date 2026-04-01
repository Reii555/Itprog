<?php
// CLIENT-SIDE IT SUPPORT PAGE
// Allows students to submit a support ticket.
// @isabel cubs

session_start();

// If form is submitted
if(isset($_POST['submit_ticket'])) {
    $issue = trim($_POST['issue']);

    if(!empty($issue)) {
        // Optional: You can save $issue to a database or send email here

        // Alert and redirect to login
        echo "<script>
            alert('Your ticket has been submitted.\\n\\nFor more inquiries contact:\\nIT Support Hotline\\nPhone no.: (63+) 1234-5678\\nEmail: itsupport@csp.edu.ph');
            window.location.href='login.php';
        </script>";
        exit();
    } else {
        echo "<script>alert('Please describe your issue before submitting.');</script>";
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>IT Support - Centralized Scholarship Program</title>
    <link rel="stylesheet" href="client_support.css">
</head>
<body>
    <header>
        <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
    </header>

    <section id="itSupport">
        <h3>IT Support</h3>
        <p>Please specify your issue below:</p>

        <form method="POST" action="">
            <textarea name="issue" rows="6" placeholder="Describe your issue..." required></textarea>
            <br>
            <input type="submit" name="submit_ticket" value="Submit Ticket" />
        </form>
    </section>
</body>
</html>
<?php
    session_start();

    function redirectToLogin() {
                header("Location: ../Login.php");
                exit();
            }

            function redirectToDashboard() {
                header("Location: ../Dashboard.php");
                exit();
            }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IT Support</title>
    <link rel="stylesheet" href="IT_Support_Design.css">
</head>
<body>
    <div class="header">
    <img src="../icons/CSP_logo.png" alt="CSP Logo" class="CSP-logo">
    <h3>Centralized Scholarship Portal / IT Support</h3>
</div>

    </br>

    <h1>IT Support</h1>

    <section class="title-label">
        <h3>Specify issue</h3>
    </section>

   <section class="issue-form">
    <form id="ticketForm" onsubmit="return submitTicket(event)">
        <div class="form-container">
            <textarea name="issue" id="issueText" rows="8" cols="50" required></textarea>
            <div class="button-wrapper">
                <button type="submit" class="submit-btn">Send out a ticket</button>
            </div>
        </div>
    </form>
    
     <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ticket Submitted !!</h3>
            </div>
            <div class="modal-body">
                <p>For more inquiries contact: <strong>IT Support Hotline</strong></p>
                <p class="contact-number">Phone no.: (63+) 1234-5678</p>
                <p class="contact-email"><strong>Email: itsupport@csp.edu.ph</strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()">Submit another Ticket?</button>
            <?php 

            if(isset($_SESSION['account_id']) && isset($_SESSION['logged_in'])) {
                // User is logged in
                echo '<button onclick="window.location.href=\'Server_Dashboard.php\'">Return to Dashboard</button>';
            } else if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                // User is not logged in
                echo '<button onclick="window.location.href=\'login.php\'">Return to Login</button>';
            }

            ?>
            </div>
            
        </div>
    </div>

    <script>
        function submitTicket(event) {
            event.preventDefault(); // Prevent form from actually submitting
            
            const issueText = document.getElementById('issueText').value;
            
            if (issueText.trim() === "") {
                alert("Please describe your issue before submitting.");
                return false;
            }
            
            // Show the confirmation modal
            showModal();
            
            document.getElementById('issueText').value = "";
            
            return false;
        }
        
        function showModal() {
            const modal = document.getElementById('confirmationModal');
            modal.style.display = 'block';
            
            // Auto-close after 10 seconds
            setTimeout(function() {
                closeModal();
            }, 10000);
        }
        
        function closeModal() {
            const modal = document.getElementById('confirmationModal');
            modal.style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('confirmationModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</body>
</html>
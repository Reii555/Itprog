<?php  
// CLIENT-SIDE HOME PAGE
// Displays the home page of the CSP, showing ongoing scholarships and the user's applications.
// @isabel cubs

session_start();
include("../../db_connect.php");

if(!isset($_SESSION['student_acc_id'])){
    header("Location: login.php");
    exit();
}

$getOngoingSc = "SELECT * FROM SCHOLARSHIPS WHERE status='Ongoing' ORDER BY deadline ASC LIMIT 3";
$ongoingSc = mysqli_query($conn, $getOngoingSc);

$account_id = $_SESSION['student_acc_id'];
$getStudID = "SELECT student_id FROM STUDENTS WHERE account_id='$account_id'";
$StudID = mysqli_query($conn, $getStudID);
$cell = mysqli_fetch_assoc($StudID);
$student_id = $cell['student_id'];

$getApp = "SELECT a.*, s.title, s.deadline 
           FROM APPLICATIONS a
           JOIN SCHOLARSHIPS s ON a.scholarship_id = s.scholarship_id
           WHERE a.student_id='$student_id'
           ORDER BY a.submission_date DESC
           LIMIT 3";
$AppSc = mysqli_query($conn, $getApp);

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client_home.css">
    </head>
    <body>
        <header>
            <section class="brand">
                <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
                <h1>Centralized Scholarship Portal</h1>
            </section>
            <nav>
                <a id="active" href="home.php">Home</a> |
                <a href="scholarList.php">Scholarships</a> |      
                <a href="your-applications.php">Your Applications</a> |
                <a href="user-profile.php">Profile</a>
            </nav>
        </header>

        <section id="homeSection">
            <section>
                <h3>Find & Apply for Scholarships</h3>
                <p>Access updated information on ongoing and upcoming scholarship opportunities in one centralized platform.</p>
                <a class="buttons" href="scholarList.php">Scholarship Listing</a>
            </section>
            <section>
            </section>
        </section>

        <section id="ongoingSection">
            <h4>Ongoing Scholarships</h4>
            <section class="ongoingContainer">
                <?php
                if(mysqli_num_rows($ongoingSc) > 0){
                    while($row = mysqli_fetch_assoc($ongoingSc)){
                ?>

                <section class="scholarshipBox">
                    <h5><?php echo $row['title']; ?></h5>
                    <p class="deadlineText">Deadline: <?php echo $row['deadline']; ?></p>
                    <p><?php echo $row['description']; ?></p>

                    <a class="buttons" href="ongoingSch.php?id=<?php echo $row['scholarship_id']; ?>">View Details</a> 
                </section>
                <?php
                    }
                echo "</section>";
                echo "<a class='buttons2' href='scholarList.php'>View All Scholarships</a>";

                } else {
                   echo "<p>No ongoing scholarships available.</p>";
                    echo "</section>";
                }
                ?>
        </section>

        <section id="appsSection">
            <h4>Your Applications</h4>
            <section class="appsContainer">
                <?php
                if(mysqli_num_rows($AppSc) > 0){
                    while($row = mysqli_fetch_assoc($AppSc)){
                ?>

                <section class="appsBox">
                    <h5><?php echo $row['title']; ?></h5>
                    <p>Status: <?php echo $row['status']; ?></p>
                    <p class="deadlineText">Deadline: <?php echo $row['deadline']; ?></p>
                    <p>Submitted on: <?php echo $row['submission_date']; ?></p>
                    <section class="appButtons">
                        <a class="buttons2" href="your-applications-read.php?id=<?php echo $row['application_id']; ?>">View Application</a>
                        <?php
                        if ($row['status'] == 'Draft'){
                            echo "<a class='buttons2' href='application-form.php?id={$row['application_id']}'>Edit Application</a>";
                        }
                        ?>
                    </section>  
                </section>

                <?php
                    }
                echo "</section>";
                echo "<a class='buttons3' href='your-applications.php'>View All Applications</a>";

                } else {
                    echo "<p id='none'>No applications yet.</p>";
                    echo "</section>";
                }
                ?>
        </section>

        <footer>
            <section class="footerContainer">
                <section>
                    <h4>Centralized Scholarship Program</h4>
                    <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
                </section>
                <section>
                    <h5>About us</h5>
                     <p>
                        The Centralized Scholarship Portal serves as a centralized
                        scholarship system designed for a specific organization,
                        providing students with a single platform where they can
                        view past, ongoing, and upcoming scholarship opportunities.
                    </p>
                </section>
                <section>
                    <h5>Links</h5>
                    <a href="home.php">Home</a>                
                    <a href="scholarList.php">Scholarships</a>       
                    <a href="your-applications.php">Your Applications</a>   
                    <a href="user-profile.php">Profile</a>      
                </section>
                <section>
                    <h5>Contact us</h5>
                    <p>Phone no.: (63+) 1234-5678</p>
                    <p>Email: itsupport@csp.edu.ph</p>
                </section>
            </section>

            <p id="copyright">@2026CentralizedScholarshipPortal</p>
        </footer>
    </body>
</html>
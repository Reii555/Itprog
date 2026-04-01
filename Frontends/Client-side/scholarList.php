<?php  
// CLIENT-SIDE SCHOLARSHIP LISTING PAGE
// A centralized page to view all of the ongoing, upcoming, and past scholarships.
// @isabel cubs

session_start();
include("../../db_connect.php");

if(!isset($_SESSION['account_id'])){
    header("Location: login.php");
    exit();
}

$getOngoingSc = "SELECT * FROM SCHOLARSHIPS WHERE status='Ongoing' ORDER BY deadline ASC";
$ongoingSc = mysqli_query($conn, $getOngoingSc);

$getUpSc = "SELECT * FROM SCHOLARSHIPS WHERE status='Upcoming'";
$upSc = mysqli_query($conn, $getUpSc);

$getPastSc = "SELECT * FROM SCHOLARSHIPS WHERE status='Completed' ORDER BY deadline ASC";
$pastSc = mysqli_query($conn, $getPastSc);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Scholarship Listing - Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client_scholarList.css">
    </head>
    <body>
        <header>
            <section class="brand">
                <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
                <h1>Centralized Scholarship Portal</h1>
            </section>
            <nav>
                <a href="home.php">Home</a> |
                <a id="active" href="scholarList.php">Scholarships</a> |      
                <a href="your-applications.php">Your Applications</a> |
                <a href="user-profile.php">Profile</a> |
            </nav>
        </header>

        <section id="heading">
            <h3>Scholarship Listings</h3>
        </section>

        <section id="jumpSection">
            <p>jump to:</p>
            <section>
                <a class="buttons1" href="#ongoingSection">Ongoing</a>
                <a class="buttons1" href="#upcomingSection">Upcoming</a>
                <a class="buttons1" href="#pastSection">Past</a>
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
                } else {
                    echo "<p>No ongoing scholarships available.</p>";
                }
                ?>
            </section>
        </section>

        <section id="upcomingSection">
            <h4>Upcoming Scholarships</h4>
            <section class="upcomingContainer">
                <?php
                if(mysqli_num_rows($upSc) > 0){
                    while($row = mysqli_fetch_assoc($upSc)){
                ?>

                <section class="scholarshipBox">
                    <h5><?php echo $row['title']; ?></h5>
                    <p class="deadlineText">Deadline: <?php echo $row['deadline']; ?></p>
                    <p><?php echo $row['description']; ?></p>
                </section>

                <?php
                    }
                } else {
                    echo "<p id='none'>No upcoming scholarships available.</p>";
                }
                ?>
            </section>
        </section>

        <section id="pastSection">
            <h4>Past Scholarships</h4>
            <section class="pastContainer">
                <?php
                if(mysqli_num_rows($pastSc) > 0){
                    while($row = mysqli_fetch_assoc($pastSc)){
                ?>

                <section class="scholarshipBox">
                    <h5><?php echo $row['title']; ?></h5>
                    <p class="deadlineText">Deadline: <?php echo $row['deadline']; ?></p>
                    <p><?php echo $row['description']; ?></p>
                    <a class="buttons" href="pastSch.php?id=<?php echo $row['scholarship_id']; ?>">View Details</a> 
                </section>

                <?php
                    }
                } else {
                    echo "<p>No completed scholarships available.</p>";
                }
                ?>
            </section>
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
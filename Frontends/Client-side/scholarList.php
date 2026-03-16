<?php  
// CLIENT-SIDE SCHOLARSHIP LISTING PAGE
// A centralized page to view all of the ongoing, upcoming, and past scholarships.
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client_scholarList.css">
    </head>
    <body>
        <header>
            <section class="brand">
                <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
                <h1>Centralized Scholarship Portal</h1>
            </section>
            <nav>
                <a href="home.php">Home</a>
                <a id="active" href="scholarList.php">Scholarships</a>         <!-- insert href link to scholarship listing page -->
                <a>Your Applications</a>    <!-- insert href link to users application page -->
                <a>Profile</a>              <!-- insert href link to user profile page -->
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
                <section class="scholarshipBox">
                    <img src="../icons/temp_image.png" class="schImage" alt="Scholarship Image">
                    <h5>Scholarship Name</h5>
                    <p class="deadlineText">Deadline: xx xx, xxxx</p>
                    <p>Department/Sponsor</p>
                    <p>(short description)</p>
                    <a class="buttons" href="ongoingSch.php">View Details</a> <!-- insert href link to view ongoing scholarship page -->
                </section>
                <section class="scholarshipBox">
                    <img src="../icons/temp_image.png" class="schImage" alt="Scholarship Image">
                    <h5>Scholarship Name</h5>
                    <p class="deadlineText">Deadline: xx xx, xxxx</p>
                    <p>Department/Sponsor</p>
                    <p>(short description)</p>
                    <a class="buttons" href="ongoingSch.php">View Details</a> <!-- insert href link to view ongoing scholarship page -->
                </section>
                <section class="scholarshipBox">
                    <img src="../icons/temp_image.png" class="schImage" alt="Scholarship Image">
                    <h5>Scholarship Name</h5>
                    <p class="deadlineText">Deadline: xx xx, xxxx</p>
                    <p>Department/Sponsor</p>
                    <p>(short description)</p>
                    <a class="buttons" href="ongoingSch.php">View Details</a> <!-- insert href link to view ongoing scholarship page -->
                </section>
            </section>
        </section>

        <section id="upcomingSection">
            <h4>Upcoming Scholarships</h4>
            <section class="upcomingContainer">
                <section class="scholarshipBox">
                    <h5>Scholarship Name</h5>
                    <p>Release Date: xx xx, xxxx</p>
                </section>
                <section class="scholarshipBox">
                    <h5>Scholarship Name</h5>
                    <p>Release Date: xx xx, xxxx</p>
                </section>
            </section>
        </section>

        <section id="pastSection">
            <h4>Past Scholarships</h4>
            <section class="pastContainer">
                <section class="scholarshipBox">
                    <h5>Scholarship Name</h5>
                    <p>Start Date: xx xx, xxxx</p>
                    <p>Deadline: xx xx, xxxx</p>
                    <a class="buttons" href="pastSch.php">View Details</a> <!-- insert href link to view past scholarship page -->
                </section>
                <section class="scholarshipBox">
                    <h5>Scholarship Name</h5>
                    <p>Start Date: xx xx, xxxx</p>
                    <p>Deadline: xx xx, xxxx</p>
                    <a class="buttons" href="pastSch.php">View Details</a> <!-- insert href link to view past scholarship page -->
                </section>
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
                    <a>Your Applications</a>    <!-- insert href link to ? page -->
                    <a>Profile</a>              <!-- insert href link to ? page -->
                </section>
                <section>
                    <h5>Contact us</h5>
                    <p>Phone number: xxx-xxx-xxxx</p>
                    <p>Email: xxxx@xxxxx.xxx</p>
                    <p>(social media links)</p>
                </section>
            </section>

            <p id="copyright">@2026CentralizedScholarshipPortal</p>
        </footer>
    </body>
</html>
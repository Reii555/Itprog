<?php  
// CLIENT-SIDE HOME PAGE
// Displays the home page of the CSP, which includes a few number of ongoing scholarships and the users applications.
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
                <a id="active" href="home.php">Home</a>
                <a href="scholarList.php">Scholarships</a>      
                <a>Your Applications</a>    <!-- insert href link to users application page -->
                <a>Profile</a>              <!-- insert href link to user profile page -->
            </nav>
        </header>

        <section id="homeSection">
            <section>
                <h3>Find & Apply for Scholarships</h3>
                <p>Access updated information on ongoing and upcoming scholarship opportunities in one centralized platform.</p>
                <a class="buttons" href="scholarList.php">Scholarship Listing</a>
            </section>
            <section>
                <img src="../icons/temp_image.png" class="homeImage" alt="Scholarship Image">
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
            <a class="buttons2" href="scholarList.php">View All Scholarships</a>
        </section>

        <section id="appsSection">
            <h4>Your Applications</h4>
            <section class="appsContainer">
                <section class="appsBox">
                    <h5>Scholarship Name</h5>
                    <p>Status: ---</p>
                    <p class="deadlineText2">Deadline: xx xx, xxxx</p>
                    <p>Submitted on: xx xx, xxxx</p>
                    <section class="appButtons">
                        <a class="buttons2">View Applications</a>
                        <a class="buttons2">Edit Applications</a>
                    </section>  
                </section>
                <section class="appsBox">
                    <h5>Scholarship Name</h5>
                    <p>Status: ---</p>
                    <p class="deadlineText2">Deadline: xx xx, xxxx</p>
                    <p>Submitted on: xx xx, xxxx</p>
                    <section class="appButtons">
                        <a class="buttons2">View Applications</a>
                        <a class="buttons2">Edit Applications</a>
                    </section>  
                </section>
            </section>
             <a class="buttons3">View All Applications</a> <!-- insert href link to your applications page -->
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
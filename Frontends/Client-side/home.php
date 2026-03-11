<?php  
// CLIENT-SIDE HOME PAGE
// Displays the home page of the CSP, which includes the ongoing scholarship and the users application.
// @isabel cubangbang
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
                <a id="active" href="./Home">Home</a>
                <a>Scholarships</a>         <!-- insert href link to scholarship listing page -->
                <a>Your Applications</a>    <!-- insert href link to users application page -->
                <a>Profile</a>              <!-- insert href link to user profile page -->
            </nav>
        </header>

        <section id="homeSection">
            <section>
                <h3>Find & Apply for Scholarships</h3>
                <p>Access updated information on ongoing and upcoming scholarship opportunities in one centralized platform.</p>
                <a class="buttons">Scholarship Listing</a> <!-- insert href link to scholarship listing page -->
            </section>
            <section>
                <img src="../icons/temp_image.png" class="homeImage" alt="Scholarship Image">
            </section>
        </section>

        <section id="ongoingSection">
            <h4>Ongoing Scholarships</h4>
            <section class="scholarshipContainer">
                <section class="scholarshipBox">
                    <img src="../icons/temp_image.png" class="schImage" alt="Scholarship Image">
                    <h5>Scholarship Name</h5>
                    <p class="deadlineText">Deadline: xx xx, xxxx</p>
                    <p>Department/Sponsor</p>
                    <p>(short description)</p>
                    <a class="buttons">View Details</a> <!-- insert href link to view ongoing scholarship page -->
                </section>
                <section class="scholarshipBox">
                    <img src="../icons/temp_image.png" class="schImage" alt="Scholarship Image">
                    <h5>Scholarship Name</h5>
                    <p class="deadlineText">Deadline: xx xx, xxxx</p>
                    <p>Department/Sponsor</p>
                    <p>(short description)</p>
                    <a class="buttons">View Details</a> <!-- insert href link to view ongoing scholarship page -->
                </section>
                <section class="scholarshipBox">
                    <img src="../icons/temp_image.png" class="schImage" alt="Scholarship Image">
                    <h5>Scholarship Name</h5>
                    <p class="deadlineText">Deadline: xx xx, xxxx</p>
                    <p>Department/Sponsor</p>
                    <p>(short description)</p>
                    <a class="buttons">View Details</a> <!-- insert href link to view ongoing scholarship page -->
                </section>
            </section>
            <a class="buttons2">View All Scholarships</a> <!-- insert href link to scholarship listing page -->
        </section>
    </body>
</html>
<?php  
// CLIENT-SIDE HOME PAGE
// Displays the details of an ongoing scholarship, and gives the user the option to apply for it.
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client_scholarship.css">
    </head>
    <body>
        <header>
            <section class="brand">
                <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
                <h1>Centralized Scholarship Portal</h1>
            </section>
            <nav>
                <a href="home.php">Home</a>
                <a href="scholarList.php">Scholarships</a>      
                <a>Your Applications</a>    <!-- insert href link to users application page -->
                <a>Profile</a>              <!-- insert href link to user profile page -->
            </nav>
        </header>

        <section id="scholarshipBox">
            <h4>Scholarship Name</h4>
            <p class="deadlineText">Deadline: xx xx, xxxx</p>
            <p>Department/Sponsor</p>
            <p>Contact Person</p>
            <p>(short description)</p>

            <section id="detailBox">
                <section>
                    <p>Eligibility</p>
                    <ul><li>item1</li></ul>
                </section>
                <section>
                    <p>Requirements</p>
                    <ul><li>item1</li></ul>
                </section>
            </section>
            
            <a class="buttons" href="scholarList.php">Apply Now</a> <!-- update href link -->
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
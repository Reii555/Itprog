<?php  
// CLIENT-SIDE HOME PAGE
// Displays the details of an ongoing scholarship, and gives the user the option to apply for it.
// @isabel cubs

session_start();
include("../../db_connect.php");

if(!isset($_SESSION['student_acc_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    echo "No scholarship selected.";
    exit();
}

$scholarship_id = $_GET['id'];

$getScholar = "SELECT * FROM SCHOLARSHIPS WHERE scholarship_id='$scholarship_id'";
$result = mysqli_query($conn, $getScholar);

if(mysqli_num_rows($result) == 0){
    echo "Scholarship not found.";
    exit();
}

$scholar = mysqli_fetch_assoc($result);

$account_id = $_SESSION['student_acc_id'];
$getStudID = mysqli_query($conn, "SELECT student_id FROM STUDENTS WHERE account_id='$account_id'");

if ($getStudID && mysqli_num_rows($getStudID) > 0) {
    $row = mysqli_fetch_assoc($getStudID);
    $student_id = $row['student_id'];
} else {
    die("Student record not found.");
}

$appCheckQuery = "SELECT * FROM APPLICATIONS 
                  WHERE scholarship_id = $scholarship_id 
                  AND student_id = $student_id";

$appResult = mysqli_query($conn, $appCheckQuery);

$alreadyApplied = false;
$applicationStatus = '';

if (mysqli_num_rows($appResult) > 0) {
    $alreadyApplied = true;
    $application = mysqli_fetch_assoc($appResult);
    $applicationStatus = $application['status'];
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $scholar['title']; ?> - Centralized Scholarship Program</title>
        <link rel="stylesheet" href="client_scholarship.css">
    </head>
    <body>
        <header>
            <section class="brand">
                <img src="../icons/CSP_logo.png" id="logo" alt="logo icon">
                <h1>Centralized Scholarship Portal</h1>
            </section>
            <nav>
                <a href="home.php">Home</a> |
                <a href="scholarList.php">Scholarships</a> |      
                <a href="your-applications.php">Your Applications</a> |
                <a href="user-profile.php">Profile</a>
            </nav>
        </header>

        <section id="scholarshipBox">
            <h4><?php echo $scholar['title']; ?></h4>
            <p class="deadlineText">Deadline: <?php echo $scholar['deadline']; ?></p>
            <p><?php echo $scholar['description']; ?></p>

            <section id="detailBox">
                <section>
                    <p>Eligibility</p>
                    <ul>
                        <?php
                        $eligibility_str = str_replace('\r\n', "\n", $scholar['eligibility']);
                        $eligibility = explode("\n", $eligibility_str);

                        foreach ($eligibility as $item) {
                            $item = trim($item);
                            if ($item !== "") {
                                echo "<li>" . htmlspecialchars($item) . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </section>
                <section>
                    <p>Requirements</p>
                    <ul>
                        <?php
                        $requirements_str = str_replace('\r\n', "\n", $scholar['requirements']);
                        $requirements = explode("\n", $requirements_str);

                        foreach ($requirements as $item) {
                            $item = trim($item);
                            if ($item !== "") {
                                echo "<li>" . htmlspecialchars($item) . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </section>
            </section>
            <?php 
            if (!$alreadyApplied) {
                echo "<a class='buttons' href='apply.php?scholarship_id=$scholarship_id'>Apply Now</a>";
            } else if ($applicationStatus == 'Draft') {
                echo "<a class='buttons' href='application-form.php?id={$application_id}'>Apply Now</a>";
            } else {
                echo "<span class='buttons disabled' title='Already applied'>Apply Now</span>";
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
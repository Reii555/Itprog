<?php
// for populating the SCHOLARSHIPS table
include("db_connect.php");

$scholarships = [
    ['STEM Achievers Grant', 'Supports students in STEM courses.', '2026-02-11 23:59:59',
    "Enrolled in STEM program\nGPA of at least 1.75",
    "Transcript of Records\nRecommendation Letter\nPersonal Statement",
    'Published', 'Completed'],

    ['Health Heroes Scholarship', 'For aspiring healthcare professionals.', '2026-03-21 23:59:59',
    "Enrolled in health-related course\nGood academic standing",
    "Transcript of Records\nPersonal Statement\nRecommendation Letter",
    'Published', 'Completed'],

    ['Tech Pioneers Scholarship', 'Supports IT and engineering students.', '2026-04-15 23:59:59',
    "Enrolled in IT/Engineering\nGPA of at least 1.75",
    "Transcript of Records\nProject portfolio\nPersonal Statement",
    'Published', 'Ongoing'],

    ['Community Builders Grant', 'For projects benefiting local communities.', '2026-04-28 23:59:59',
    "Active in community service\nHas ongoing or proposed project",
    "Project proposal\nCertificates of volunteering\nRecommendation Letter",
    'Published', 'Ongoing'],

    ['Women in Science Award', 'Encourages women pursuing science degrees.', '2026-04-28 23:59:59',
    "Female student in science field\nGPA of at least 1.75",
    "Transcript of Records\nPersonal Statement\nRecommendation Letter",
    'Published', 'Ongoing'],

    ['Digital Creators Scholarship', 'For content creators and digital innovators.', '2026-05-01 23:59:59',
    "Active digital content creator\nStudent in any program",
    "Portfolio of content\nTranscript of Records\nStatement of purpose",
    'Published', 'Ongoing'],

    ['Global Scholars Program', 'For students planning international studies.', '2026-05-03 23:59:59',
    "Planning or accepted for international study\nGood academic standing",
    "Acceptance letter\nTranscript of Records\nPersonal Statement",
    'Published', 'Ongoing'],

    ['Future Innovators Grant', 'For inventive and tech-forward projects.', '2026-06-01 23:59:59',
    "With innovative tech project\nOpen to all students",
    "Project proposal\nCV\nTranscript of Records",
    'Draft', 'Upcoming'],

    ['Leadership Excellence Award', 'Recognizes future leaders in any field.', '2026-06-15 23:59:59',
    "Demonstrated leadership skills\nActive in organizations",
    "Leadership resume\nRecommendation Letters\nPersonal Statement",
    'Draft', 'Upcoming'],

    ['Cultural Ambassadors Scholarship', 'Promotes cultural exchange and arts.', '2026-07-01 23:59:59',
    "Involved in cultural/art programs\nOpen to all students",
    "Portfolio or proof of participation\nCertificates\nPersonal Statement",
    'Draft', 'Upcoming'],

    ['Green Tech Innovators Grant', 'Supports sustainability tech ideas.', '2026-08-10 23:59:59',
    "With green technology idea\nOpen to STEM students",
    "Project proposal\nCV\nTranscript of Records",
    'Draft', 'Upcoming'],

    ['Next Gen Scientists Award', 'For promising young science students.', '2026-08-30 23:59:59',
    "Enrolled in STEM program\nStrong academic performance",
    "Transcript of Records\nResearch proposal\nRecommendation Letter",
    'Draft', 'Upcoming'],
];

foreach ($scholarships as $scholar) {
    $title = mysqli_real_escape_string($conn, $scholar[0]);
    $desc = mysqli_real_escape_string($conn, $scholar[1]);
    $deadline = $scholar[2];
    $eligibility = $scholar[3];
    $requirements = $scholar[4];
    $release_status = $scholar[5];
    $status = $scholar[6];
    $admin_id = 1;
    $created_at = '2026-01-11 9:00:00';

    $sql = "INSERT INTO SCHOLARSHIPS (title, description, deadline, eligibility, requirements, release_status, status, created_by, created_at)
            VALUES ('$title', '$desc', '$deadline', '$eligibility', '$requirements','$release_status', '$status', $admin_id, '$created_at')";

    if (!mysqli_query($conn, $sql)) {
        echo "Error inserting '$title': " . mysqli_error($conn) . "<br>";
    }
}

echo "Scholarships inserted successfully!";
?>
<!--

VIEW APPLICATION DOCUMENTS

This file is responsible for displaying the documents associated with 
a specific application. This is made so that when administrators review 
applications, they can click on the document links to view the files 
directly in the browser. This retrieves the document information from 
the database based on the provided document ID and serves the file with 
the appropriate headers to allow inline viewing for supported 
file types (PDF, images) or force download for unsupported types. 
If the document is not found, an error message is displayed with a 
link to return to the applications page.

-->

<?php
include("../../db_connect.php");

if(!isset($_GET['doc_id'])) {
    die('No document specified');
}

$document_id = (int)$_GET['doc_id'];

// Get document info
$query = "SELECT file_name, file_type, docu_type, application_id 
          FROM DOCUMENTS 
          WHERE document_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $document_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$document = mysqli_fetch_assoc($result);

if(!$document) {
    die('Document not found');
}

// Define where files are stored
$file_path = "../../uploads/documents/" . $document['file_name'];

if(file_exists($file_path)) {
    $file_ext = strtolower(pathinfo($document['file_name'], PATHINFO_EXTENSION));
    
    switch($file_ext) {
        case 'pdf':
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $document['file_name'] . '"');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            header('Content-Disposition: inline; filename="' . $document['file_name'] . '"');
            break;
        case 'png':
            header('Content-Type: image/png');
            header('Content-Disposition: inline; filename="' . $document['file_name'] . '"');
            break;
        default:
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $document['file_name'] . '"');
    }
    
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit();
} else {
    // Show error page with back link
    echo "<!DOCTYPE html>";
    echo "<html><head><title>Document Not Found</title>";
    echo "<style>body{font-family: Arial; text-align: center; padding: 50px;}</style>";
    echo "</head><body>";
    echo "<h2>Document Not Found</h2>";
    echo "<p>The file could not be located on the server.</p>";
    
    if(isset($_GET['return'])) {
        echo "<a href='" . htmlspecialchars($_GET['return']) . "'>← Back to Applications</a>";
    } else {
        echo "<a href='AppMgmt.php'>← Back to Applications</a>";
    }
    echo "</body></html>";
}
?>
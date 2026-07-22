<?php
session_start();
include "db.php";
include "send_email.php";


if(isset($_POST['submit'])){

    $user_id = $_SESSION['user_id'];

   $crime_type = mysqli_real_escape_string($conn, trim($_POST['crime_type']));

$title = mysqli_real_escape_string($conn, trim($_POST['title']));

$description = mysqli_real_escape_string($conn, trim($_POST['description']));

$priority = mysqli_real_escape_string($conn, trim($_POST['priority']));

    // Check all required fields
    if(
    empty($crime_type) ||
    $crime_type == "Select Crime Type" ||
    empty($title) ||
    empty($description) ||
    empty($priority) ||
    empty($_FILES['evidence']['name'])
){

        echo "<script>
        alert('Please fill in all fields and upload evidence.');
        window.location='report.php';
        </script>";
        exit();
    }

    $filename = time() . "_" . basename($_FILES['evidence']['name']);
$tmp_name = $_FILES['evidence']['tmp_name'];
$file_size = $_FILES['evidence']['size'];

$max_size = 50 * 1024 * 1024; // 50 MB

$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

$allowed = array(
    "jpg","jpeg","png","gif","webp","bmp",
    "pdf",
    "doc","docx",
    "xls","xlsx",
    "ppt","pptx",
    "txt","csv",
    "zip","rar","7z",
    "mp3","wav",
    "mp4","avi","mov","mkv","wmv"
);

if($file_size > $max_size){
    die("<script>alert('Maximum file size is 50 MB.'); window.location='report.php';</script>");
}

if(!in_array($file_ext,$allowed)){
    die("<script>alert('This file type is not allowed.'); window.location='report.php';</script>");
}

// Save uploaded evidence

if(!move_uploaded_file($tmp_name, "uploads/".$filename)){
    die("<script>
        alert('Failed to upload evidence.');
        window.location='report.php';
    </script>");
}


$sql = "INSERT INTO complaints
(user_id, title, description, category, priority, file, status)
VALUES
(
    '$user_id',
    '$title',
    '$description',
    '$crime_type',
    '$priority',
    '$filename',
    'Pending'
)";

if(mysqli_query($conn,$sql)){

    // Get user information
    $userQuery = mysqli_query($conn, "SELECT name, email FROM users WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($userQuery);

    $subject = "CyberShield Complaint Submitted Successfully";

    $body = "
    <h2>Complaint Submitted Successfully</h2>

    <p>Dear <strong>{$user['name']}</strong>,</p>

    <p>Your complaint has been successfully submitted to <b>CyberShield</b>.</p>

    <hr>

    <p><strong>Complaint Title:</strong> $title</p>
    <p><strong>Crime Type:</strong> $crime_type</p>
    <p><strong>Priority:</strong> $priority</p>
    <p><strong>Status:</strong> Pending</p>

    <hr>

    <p>Our team will review your complaint and notify you whenever its status changes.</p>

    <br>

    <p>Regards,<br><b>CyberShield Team</b></p>
    ";

    sendEmail($user['email'], $user['name'], $subject, $body);

    header("Location: my_complaints.php");
    exit();

}else{

    die("Database Error: ".mysqli_error($conn));

}
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Crime - CyberShield</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-page">
    
<!-- TOP NAVBAR -->

<div class="dashboard-navbar">

    <div class="dashboard-logo">

        <img src="shield.png.png" alt="CyberShield">

        <h2>CyberShield</h2>

    </div>

    <div class="dashboard-user">

        <div class="welcome-text">

            <small>Welcome,</small>

            <h3><?php echo ucfirst($_SESSION['name']); ?></h3>

        </div>

        <img src="dp.png" alt="Profile">

    </div>

</div>


<!-- SIDEBAR (NO CYBERSHIELD TEXT NOW) -->
<div class="sidebar">

    <img src="shield.png.png" width="80" style="display:block;margin:auto;">

    <h2 style="text-align:center;">CyberShield</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="report.php" class="active">Submit Complaint</a>

    <a href="my_complaints.php">My Complaints</a>

    <a href="profile.php">My Profile</a>

    <a href="logout.php">Logout</a>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    

        <div class="report-box">
    <h2>Report a Cyber Crime</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Crime Type</label>
        <select name="crime_type" required>

            <option value="">Select Crime Type</option>

            <option>Hacking</option>
            <option>Fraud</option>
            <option>Cyberbullying</option>

        </select>

        <label>Title</label>
        <input
        type="text"
        name="title"
        placeholder="Enter title"
        required>

        <label>Priority</label>

<select name="priority" required>

    <option value="">Select Priority</option>

    <option value="Low">Low</option>

    <option value="Medium">Medium</option>

    <option value="High">High</option>

    <option value="Critical">Critical</option>

</select>

        <label>Description</label>
        <textarea
        name="description"
        placeholder="Describe your issue..."
        required></textarea>

        <label>Upload Evidence</label>
        <input
        type="file"
        name="evidence"
        required
        accept=".jpg,.jpeg,.png,.gif,.webp,.bmp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar,.7z,.mp3,.wav,.mp4,.avi,.mov,.mkv,.wmv">

        <button
        type="submit"
        name="submit">

            Submit Report

        </button>

    </form>

</div>
</body>

</html>
<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$userQuery = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($userQuery);

// Get user complaints
$result = mysqli_query($conn,"
SELECT *
FROM complaints
WHERE user_id='$user_id'
ORDER BY created_at DESC
");

// Statistics
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM complaints
WHERE user_id='$user_id'
"));

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM complaints
WHERE user_id='$user_id'
AND status='Pending'
"));

$resolved = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM complaints
WHERE user_id='$user_id'
AND status='Resolved'
"));

$progress = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM complaints
WHERE user_id='$user_id'
AND status='In Progress'
"));

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Complaints - CyberShield</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="dashboard-page">

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <img src="shield.png.png" width="80" style="display:block;margin:auto;">

    <h2 style="text-align:center;">CyberShield</h2>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="report.php">
        Submit Complaint
    </a>

    <a href="my_complaints.php" class="active">
        My Complaints
    </a>

    <a href="profile.php">
        My Profile
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>

<!-- ================= MAIN ================= -->

<div class="main">

<!-- ================= TOP NAVBAR ================= -->

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

<!-- ================= PAGE TITLE ================= -->

<h1>My Complaints</h1>

<p>Track all your submitted cyber crime reports from one place.</p>

<!-- ================= SUMMARY CARDS ================= -->

<div class="cards">

    <div class="card-box">

        <h3>Total Complaints</h3>

        <p><?php echo $total['total']; ?></p>

    </div>

    <div class="card-box">

        <h3>Pending</h3>

        <p><?php echo $pending['total']; ?></p>

    </div>
    
    <div class="card-box">

    <h3>In Progress</h3>

    <p><?php echo $progress['total']; ?></p>

</div>

    <div class="card-box">

        <h3>Resolved</h3>

        <p><?php echo $resolved['total']; ?></p>

    </div>

</div>

<!-- ================= COMPLAINT TABLE ================= -->

<div class="activity-box">

<h3>Your Complaint History</h3>

<table>

<tr>

<th>ID</th>

<th>Title</th>

<th>Description</th>

<th>Category</th>

<th>Priority</th>

<th>Status</th>

<th>Date</th>

</tr><?php

if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

?>

<tr>

    <td>#<?php echo $row['id']; ?></td>

    <td>

        <?php echo htmlspecialchars($row['title']); ?>

    </td>

    <td>

        <?php

        if(strlen($row['description']) > 60){

            echo htmlspecialchars(substr($row['description'],0,60))."...";

        }else{

            echo htmlspecialchars($row['description']);

        }

        ?>

    </td>

    <td>

        <?php echo htmlspecialchars($row['category']); ?>

    </td>

    <td>

        <?php echo htmlspecialchars($row['priority']); ?>

    </td>

    <td>

        <?php

        if($row['status']=="Resolved"){

            echo "<span class='resolved'>Resolved</span>";

        }
        elseif($row['status']=="Pending"){

            echo "<span class='pending'>Pending</span>";

        }
        else{

            echo "<span class='progress'>In Progress</span>";

        }

        ?>

    </td>

    <td>

        <?php echo date("d M Y", strtotime($row['created_at'])); ?>

    </td>

</tr>

<?php

    }

}else{

?>

<tr>

    <td colspan="7" style="text-align:center;padding:40px;">

        <h3>No Complaints Found</h3>

        <p style="margin-top:10px;color:#b7c5da;">

            You haven't submitted any complaints yet.

        </p>

    </td>

</tr>

<?php

}

?>

</table>

</div><!-- ================= BOTTOM SECTION ================= -->

<div class="dashboard-bottom">

    <!-- Complaint Information -->

    <div class="activity-box">

        <h3>Complaint Information</h3>

        <p style="color:#b7c5da; line-height:1.9; margin-top:15px;">

            <strong>Pending</strong> – Your complaint has been received and is waiting for review.
            <br><br>

            <strong>In Progress</strong> – Your complaint is currently being investigated.
            <br><br>

            <strong>Resolved</strong> – Your complaint has been successfully resolved.
            <br><br>

            Thank you for helping make the digital world safer with
            <strong>CyberShield.</strong>

        </p>

    </div>

    <!-- Quick Actions -->

    <div class="quick-actions">

        <h2>Quick Actions</h2>

        <a href="report.php" class="quick-btn">

            Submit New Complaint

        </a>

        <a href="dashboard.php" class="quick-btn">

            Dashboard

        </a>

        <a href="profile.php" class="quick-btn">

            My Profile

        </a>

    </div>

</div>

</div>

</body>

</html>
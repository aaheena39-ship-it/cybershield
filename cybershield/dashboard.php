<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Dashboard Counts */

$total = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM complaints WHERE user_id='$user_id'"));

$pending = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM complaints WHERE user_id='$user_id' AND status='Pending'"));

$resolved = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM complaints WHERE user_id='$user_id' AND status='Resolved'"));
?>

<!DOCTYPE html>
<html>

<head>

<title>Dashboard - CyberShield</title>

<link rel="stylesheet" href="style.css">

</head>

<body class="dashboard-page">

<!-- SIDEBAR -->

<div class="sidebar">

    <img src="shield.png.png" width="80" style="display:block;margin:auto;">

    <h2 style="text-align:center;">CyberShield</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="report.php">Submit Complaint</a>

    <a href="my_complaints.php">My Complaints</a>

    <a href="profile.php">My Profile</a>

    <a href="logout.php">Logout</a>

</div>

<!-- MAIN -->

<div class="main">

<!-- TOP NAVBAR -->

<div class="dashboard-navbar">

<div class="dashboard-logo">

<img src="shield.png.png">

<h2>CyberShield</h2>

</div>

<div class="dashboard-user">

<div class="welcome-text">

<small>Welcome,</small>

<h3><?php echo ucfirst($_SESSION['name']); ?></h3>

</div>

<img src="dp.png">

</div>

</div>

<!-- CARDS -->

<div class="cards">

<div class="card-box">

<h2><?php echo $total; ?></h2>

<p>Total Reports</p>

</div>

<div class="card-box">

<h2><?php echo $pending; ?></h2>

<p>In Progress</p>

</div>

<div class="card-box">

<h2><?php echo $resolved; ?></h2>

<p>Resolved</p>

</div>

</div>

<!-- DASHBOARD BOTTOM -->

<div class="dashboard-bottom">

    <!-- RECENT REPORTS -->

    <div class="recent-reports">

        <h2>Recent Reports</h2>

        <table>

            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php

            $query = mysqli_query($conn,
            "SELECT * FROM complaints
            WHERE user_id='$user_id'
            ORDER BY created_at DESC
            LIMIT 5");

            if(mysqli_num_rows($query) > 0){

                while($row = mysqli_fetch_assoc($query)){

            ?>

            <tr>

                <td><?php echo $row['title']; ?></td>

                <td><?php echo date("d M Y",strtotime($row['created_at'])); ?></td>

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

            </tr>

            <?php

                }

            }else{

                echo "<tr>
                <td colspan='3' style='text-align:center;padding:30px;color:#9fb0c8;'>
                No reports submitted yet.
                </td>
                </tr>";

            }

            ?>

        </table>

    </div>

    <!-- QUICK ACTIONS -->

    <div class="quick-actions">

        <h2>Quick Actions</h2>

        <a href="report.php" class="quick-btn">Report Crime</a>

        <a href="my_complaints.php" class="quick-btn">My Complaints</a>

        <a href="profile.php" class="quick-btn">My Profile</a>

    </div>

</div></div> <!-- END MAIN -->

</body>
</html>
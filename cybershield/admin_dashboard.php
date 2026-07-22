<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* ===============================
   DASHBOARD STATISTICS
================================= */

$totalComplaints = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints")
)['total'];

$pendingComplaints = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Pending'")
)['total'];

$resolvedComplaints = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Resolved'")
)['total'];

$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];

/* ===============================
   RECENT COMPLAINTS
================================= */

$recentComplaints = mysqli_query($conn, "
SELECT complaints.*, users.name
FROM complaints
LEFT JOIN users
ON complaints.user_id = users.id
ORDER BY complaints.id DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CyberShield Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="dashboard-page">

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <img src="shield.png.png" width="80" style="display:block;margin:auto;">

    <h2 style="text-align:center;">CyberShield</h2>

    <a href="admin_dashboard.php" class="active">
        Dashboard
    </a>

    <a href="admin_complaints.php">
        Complaints
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

        <h2>CyberShield Admin</h2>

    </div>

    <div class="dashboard-user">

        <div class="welcome-text">

            <small>Welcome,</small>

            <h3>Administrator</h3>

        </div>

        <img src="dp.png" alt="Admin">

    </div>

</div>

<!-- ================= CARDS ================= -->

<div class="cards">

    <div class="card-box">

        <h3>Total Users</h3>

        <p><?php echo $totalUsers; ?></p>

    </div>

    <div class="card-box">

        <h3>Total Complaints</h3>

        <p><?php echo $totalComplaints; ?></p>

    </div>

    <div class="card-box">

        <h3>Pending Cases</h3>

        <p><?php echo $pendingComplaints; ?></p>

    </div>

    <div class="card-box">

        <h3>Resolved Cases</h3>

        <p><?php echo $resolvedComplaints; ?></p>

    </div>
</div><!-- ================= DASHBOARD BOTTOM ================= -->

<div class="dashboard-bottom">

    <!-- ================= RECENT COMPLAINTS ================= -->

    <div class="recent-reports">

        <h2>Recent Complaints</h2>

        <table>

            <tr>

                <th>ID</th>

                <th>User</th>

                <th>Title</th>

                <th>Status</th>

                <th>Date</th>

            </tr>

            <?php

            if(mysqli_num_rows($recentComplaints)>0){

                while($row=mysqli_fetch_assoc($recentComplaints)){

            ?>

            <tr>

                <td>#<?php echo $row['id']; ?></td>

                <td><?php echo htmlspecialchars($row['name']); ?></td>

                <td><?php echo htmlspecialchars($row['title']); ?></td>

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

                    <?php echo date("d M Y",strtotime($row['created_at'])); ?>

                </td>

            </tr>

            <?php

                }

            }else{

            ?>

            <tr>

                <td colspan="5" style="text-align:center;padding:30px;color:#9fb0c8;">

                    No complaints found.

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <!-- ================= QUICK STATS ================= -->

    <div class="quick-actions">

        <h2>Quick Statistics</h2>

        <div class="small-card">

            <h4>Total Users</h4>

            <h2><?php echo $totalUsers; ?></h2>

        </div>

        <div class="small-card">

            <h4>Pending Cases</h4>

            <h2><?php echo $pendingComplaints; ?></h2>

        </div>

        <div class="small-card">

            <h4>Resolved Cases</h4>

            <h2><?php echo $resolvedComplaints; ?></h2>

        </div>

    </div>

</div>

<!-- ================= ADMIN NOTICE ================= -->

<div class="activity-box">

    <h3>Administrator Notice</h3>

    <p>

        Review pending complaints regularly, update complaint status promptly,
        and ensure CyberShield remains secure and responsive for all users.

    </p>

</div></div> <!-- END MAIN -->

</body>

</html>
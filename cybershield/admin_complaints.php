<?php
session_start();
include "db.php";
include "send_email.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* ===============================
   RESOLVE COMPLAINT
================================ */

if(isset($_GET['resolve']))
{
    $id = intval($_GET['resolve']);

    mysqli_query($conn,"
        UPDATE complaints
        SET status='Resolved'
        WHERE id='$id'
    ");

    // Get complaint and user details
    $result = mysqli_query($conn,"
        SELECT complaints.title, users.name, users.email
        FROM complaints
        JOIN users ON complaints.user_id = users.id
        WHERE complaints.id='$id'
    ");

    $data = mysqli_fetch_assoc($result);

    $subject = "CyberShield Complaint Resolved";

    $body = "
    <h2>Complaint Resolved</h2>

    <p>Dear <strong>{$data['name']}</strong>,</p>

    <p>We are pleased to inform you that your complaint has been resolved.</p>

    <hr>

    <p><strong>Complaint:</strong> {$data['title']}</p>
    <p><strong>Status:</strong> 🟢 Resolved</p>

    <hr>

    <p>Thank you for using <b>CyberShield</b>.</p>

    <p>If you need further assistance, you can submit another complaint through the CyberShield portal.</p>

    <br>

    <p><b>CyberShield Team</b></p>
    ";

    sendEmail($data['email'], $data['name'], $subject, $body);

    header("Location: admin_complaints.php");
    exit();
}


/* ===============================
   PROCESS COMPLAINT
================================ */

if(isset($_GET['process']))
{
    $id = intval($_GET['process']);

    mysqli_query($conn,"
        UPDATE complaints
        SET status='In Progress'
        WHERE id='$id'
    ");

    // Get complaint and user details
    $result = mysqli_query($conn,"
        SELECT complaints.title, users.name, users.email
        FROM complaints
        JOIN users ON complaints.user_id = users.id
        WHERE complaints.id='$id'
    ");

    $data = mysqli_fetch_assoc($result);

    $subject = "CyberShield Complaint Update";

    $body = "
    <h2>Complaint Status Updated</h2>

    <p>Dear <strong>{$data['name']}</strong>,</p>

    <p>Your complaint is now being reviewed by our investigation team.</p>

    <hr>

    <p><strong>Complaint:</strong> {$data['title']}</p>
    <p><strong>Status:</strong> 🟡 In Progress</p>

    <hr>

    <p>Thank you for your patience.</p>

    <p><b>CyberShield Team</b></p>
    ";

    sendEmail($data['email'], $data['name'], $subject, $body);

    header("Location: admin_complaints.php");
    exit();
}

/* ===============================
   DELETE COMPLAINT
================================ */

if(isset($_GET['delete']))
{
    $id = intval($_GET['delete']);

    mysqli_query($conn,"
        DELETE FROM complaints
        WHERE id='$id'
    ");

    header("Location: admin_complaints.php");
    exit();
}

/* ===============================
   GET ALL COMPLAINTS
================================ */

$result = mysqli_query($conn,"
SELECT complaints.*, users.name
FROM complaints
LEFT JOIN users
ON complaints.user_id = users.id
ORDER BY complaints.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Complaint Management - CyberShield</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="dashboard-page">

<!-- ================= NAVBAR ================= -->

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

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <img src="shield.png.png" width="80" style="display:block;margin:auto;">

    <h2 style="text-align:center;">CyberShield</h2>

    <a href="admin_dashboard.php">
        Dashboard
    </a>

    <a href="admin_complaints.php" class="active">
        Complaints
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>

<!-- ================= MAIN ================= -->

<div class="main">

<h1>Complaint Management</h1>

<p>View, review and manage all reported cyber crimes.</p><!-- ================= COMPLAINT TABLE ================= -->

<div class="activity-box">

    <h3>All Complaints</h3>

    <table>

        <tr>

            <th>ID</th>

            <th>User</th>

            <th>Title</th>

            <th>Description</th>

            <th>Status</th>

            <th>Action</th>

        </tr>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <tr>

            <td>#<?php echo $row['id']; ?></td>

            <td><?php echo htmlspecialchars($row['name']); ?></td>

            <td><?php echo htmlspecialchars($row['title']); ?></td>

            <td>

                <?php

                if(strlen($row['description']) > 60)
                {
                    echo htmlspecialchars(substr($row['description'],0,60))."...";
                }
                else
                {
                    echo htmlspecialchars($row['description']);
                }

                ?>

            </td>
            
            <a href="admin_view_complaint.php?id=<?php echo $row['id']; ?>"
class="btn-primary"
style="padding:8px 15px;font-size:13px;">

<i class="fas fa-eye"></i>

View

</a>

            <td>

                <?php

                if($row['status']=="Resolved")
                {

                    echo "<span class='status solved'>
                            <i class='fas fa-circle-check'></i>
                            Resolved
                          </span>";

                }
                elseif($row['status']=="Pending")
                {

                    echo "<span class='status pending'>
                            <i class='fas fa-clock'></i>
                            Pending
                          </span>";

                }
                else
                {

                    echo "<span class='status progress'>
                            <i class='fas fa-spinner'></i>
                            In Progress
                          </span>";

                }

                ?>

            </td>

            <td>

            <td style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">


    <?php if($row['status']=="Pending"){ ?>

    <a href="admin_complaints.php?process=<?php echo $row['id']; ?>"
       class="btn-outline"
       style="padding:8px 15px;font-size:13px;border-color:#ffaa00;color:#ffaa00;">

        <i class="fas fa-spinner"></i>

        In Process

    </a>

    <?php } ?>


    <?php if($row['status']!="Resolved"){ ?>

    <a href="admin_complaints.php?resolve=<?php echo $row['id']; ?>"
       class="btn-primary"
       style="padding:8px 15px;font-size:13px;">

        <i class="fas fa-check"></i>

        Resolve

    </a>

    <?php } ?>

                <a href="admin_complaints.php?delete=<?php echo $row['id']; ?>"
                   class="btn-outline"
                   style="padding:8px 15px;font-size:13px;border-color:#ff4d4d;color:#ff4d4d;"
                   onclick="return confirm('Are you sure you want to delete this complaint?');">

                    <i class="fas fa-trash"></i>

                    Delete

                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div><!-- ================= ADMIN NOTICE ================= -->

<div class="notice">

    <strong>Administrator Notice</strong><br><br>

    Review pending complaints regularly and update their status promptly.
    Deleted complaints cannot be recovered.

</div>

<!-- ================= QUICK SUMMARY ================= -->

<div class="small-cards">

    <div class="small-card">

        <h4>Total Complaints</h4>

        <h2>
            <?php
            $total = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS total FROM complaints"));
            echo $total['total'];
            ?>
        </h2>

        <p>Reports submitted by users.</p>

    </div>

    <div class="small-card">

        <h4>Pending Cases</h4>

        <h2>

            <?php
            $pending = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS total FROM complaints WHERE status='Pending'"));
            echo $pending['total'];
            ?>

        </h2>

        <p>Waiting for administrator review.</p>

    </div>

    <div class="small-card">

        <h4>Resolved Cases</h4>

        <h2 style="color:#4cff88;">

            <?php
            $resolved = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS total FROM complaints WHERE status='Resolved'"));
            echo $resolved['total'];
            ?>

        </h2>

        <p>Successfully completed complaints.</p>

    </div>

</div>

<!-- ================= MANAGEMENT TIPS ================= -->

<div class="activity-box" style="margin-top:30px;">

    <h3>Complaint Management Tips</h3>

    <p style="color:#b7c5da; line-height:1.9; margin-top:15px;">

        • Review every complaint carefully before marking it as resolved.<br><br>

        • Remove spam or duplicate reports to keep the database clean.<br><br>

        • Respond to users as quickly as possible for better service.<br><br>

        • Monitor suspicious activity and keep CyberShield secure at all times.

    </p>

</div>

</div> <!-- End Main -->

</body>
</html>
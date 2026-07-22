<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Profile - CyberShield</title>

    <link rel="stylesheet" href="style.css">

</head>


<body class="dashboard-page">



<div class="dashboard">



<!-- ================= SIDEBAR ================= -->


<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-logo">
        <img src="shield.png.png" class="sidebar-logo-img" alt="CyberShield Logo">
        <h2>CyberShield</h2>
    </div>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="report.php">
        Submit Complaints
    </a>

    <a href="my_complaints.php">
        My Complaints
    </a>

    <a href="profile.php" class="active">
        Profile
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>




<!-- ================= MAIN ================= -->


<div class="main-content">





<!-- ================= NAVBAR ADDED ONLY ================= -->


<div class="dashboard-navbar">


    <div class="dashboard-logo">


        <img src="shield.png.png" 
             alt="CyberShield Logo">


        <h2>
            CyberShield
        </h2>


    </div>





    <div class="dashboard-user">


        <div class="welcome-text">


            <small>
                Welcome,
            </small>


            <h3>
                <?php echo ucfirst($_SESSION['name']); ?>
            </h3>


        </div>



        <img src="dp.png" 
             alt="Profile">


    </div>



</div>

        <h2>My Profile</h2>

        <div class="profile-container">

            <!-- PROFILE CARD -->
            <div class="profile-card">
                <div class="avatar">👤</div>

                <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Joined:</strong> <?php echo $user['created_at']; ?></p>
            </div>

            <!-- CHANGE PASSWORD -->
            <div class="profile-card">

                <h3>Change Password</h3>

                <form method="POST">
                    <input type="password" name="current" placeholder="Current Password" required>
                    <input type="password" name="new" placeholder="New Password" required>
                    <input type="password" name="confirm" placeholder="Confirm Password" required>

                    <button type="submit" name="update">Update Password</button>
                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
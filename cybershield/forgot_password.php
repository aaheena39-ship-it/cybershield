<?php
include "db.php";

$message = "";
$showResetForm = false;
$email = "";

if (isset($_POST['check_email'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {

        $showResetForm = true;

    } else {

        $message = "<p style='color:red;'>Email not found.</p>";

    }

}

if (isset($_POST['reset_password'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {

        $message = "<p style='color:red;'>Passwords do not match.</p>";
        $showResetForm = true;

    } else {

        $newPassword = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn, "UPDATE users SET password='$newPassword' WHERE email='$email'");

        echo "<script>
        alert('Password Updated Successfully!');
        window.location='login.php';
        </script>";

        exit();

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Forgot Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body class="auth-page">

<div class="navbar">

    <div class="logo">
        <img src="shield.png.png" alt="">
        <span>CyberShield</span>
    </div>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>

</div>

<div class="auth-container">

<div class="card">

<h2>Reset Password</h2>

<?= $message ?>

<?php if(!$showResetForm){ ?>

<form method="POST">

<input
type="email"
name="email"
placeholder="Enter Your Email"
required>

<button
class="btn"
name="check_email">

Continue

</button>

</form>

<?php } else { ?>

<form method="POST">

<input
type="hidden"
name="email"
value="<?= $email ?>">

<input
type="password"
name="password"
placeholder="New Password"
required>

<input
type="password"
name="confirm_password"
placeholder="Confirm Password"
required>

<button
class="btn"
name="reset_password">

Update Password

</button>

</form>

<?php } ?>

<p class="bottom-text">

Remember your password?

<a href="login.php">

Login

</a>

</p>

</div>

<div class="auth-image">

<img src="shield.png.png">

</div>

</div>

</body>

</html>
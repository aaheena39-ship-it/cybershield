<?php
session_start();
include "db.php";

if (isset($_POST['send'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO contact_messages (name, email, subject, message)
              VALUES ('$name', '$email', '$subject', '$message')";

    if (mysqli_query($conn, $query)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Something went wrong!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact - CyberShield</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


 <!-- NAVBAR -->

<div class="navbar">

    <div class="logo">

        <img src="shield.png.png" alt="CyberShield Logo" class="nav-logo">

        <span>CyberShield</span>

    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="about.php">About Us</a>

        <a href="contact.php" class="active">Contact</a>

        <a href="login.php">Login</a>

        <a href="signup.php">Sign Up</a>

    </div>

</div>


<!-- CONTACT SECTION -->
<div class="contact-container">

    <!-- LEFT SIDE -->
    <div class="contact-left">
        <h1>Contact <span>Us</span></h1>

        <p><b>📍 Address:</b><br>123 Cyber Street, Secure City</p>
        <p><b>📧 Email:</b><br>cybershield540@gmail.com</p>
        <p><b>📞 Phone:</b><br>+92 344 3051971</p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="contact-right">
        <?php

if(isset($success)){

    echo "<div class='success-msg'>$success</div>";

}

if(isset($error)){

    echo "<div class='error-msg'>$error</div>";

}

?>
        <form method="POST">

            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="subject" placeholder="Subject" required>

            <textarea name="message" placeholder="Your Message" required></textarea>

            
<button type="submit" name="send">
    Send Message
</button>
        </form>
    </div>

</div>

<footer class="footer">

    <p>© 2026 CyberShield | Cyber Crime Complaint Portal</p>

    <p>Developed by <strong>Aaheena and Iqra </strong></p>

</footer>

</body>
</html>
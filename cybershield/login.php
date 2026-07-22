<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];

            header("Location: dashboard.php");
            exit();

        } else {
            echo "Wrong Password";
        }
    } else {
        echo "User not found";
    }
}
?>   <!-- ✅ THIS LINE IS IMPORTANT -->

<!DOCTYPE html>
<html>
<head>
    <title>Login - CyberShield</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">

<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        <img src="shield.png.png" alt="CyberShield">
        <span>CyberShield</span>
    </div>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="signup.php">Sign up</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>

</div>

<!-- LOGIN SECTION -->

<div class="auth-container">

    <div class="card">

        <h2>Welcome Back!</h2>
        <p class="subtitle">Login to your account</p>

        <form method="POST">

            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <div class="password-box">

    <input
        type="password"
        id="password"
        name="password"
        placeholder="Password"
        required>

    <span class="toggle-password" onclick="togglePassword()">
        👁️
    </span>

</div>

            <div class="forgot-password">
    <a href="forgot_password.php">Forgot Password?</a>
</div>

            <button class="btn" name="login">
                Login
            </button>

        </form>

        <p class="bottom-text">
            Don't have an account?
            <a href="signup.php">Sign Up</a>
        </p>

    </div>

    <div class="auth-image">

        <img src="shield.png.png" alt="Cyber Shield">

    </div>

</div>

<script>

function togglePassword() {

    var password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }

}

</script>

</body>
</html>
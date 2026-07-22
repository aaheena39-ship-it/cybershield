<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $username = trim($_POST['email']);
    $password = trim($_POST['password']);

    $res = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

    if ($row = mysqli_fetch_assoc($res)) {

        if ($password == $row['password']) {

            $_SESSION['admin_id'] = $row['id'];

            session_write_close();

            header("Location: admin_dashboard.php");
            exit();

        } else {

            $error = "Wrong Password!";

        }

    } else {

        $error = "Admin not found!";

    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login - CyberShield</title>

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

        <li><a href="contact.php">Contact</a></li>

        <li><a href="login.php">User Login</a></li>

        <li><a href="signup.php">Sign Up</a></li>

    </ul>

</div>

<!-- ADMIN LOGIN SECTION -->

<div class="auth-container"><!-- LEFT SIDE (LOGIN CARD) -->

<div class="card">

    <h2>Admin Login</h2>

    <p class="subtitle">
        Login to the CyberShield Admin Panel
    </p>

    <?php
    if(isset($error)){
        echo "<p class='error-msg'>$error</p>";
    }
    ?>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Admin Email"
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
        👁
    </span>

</div>

        <button
            type="submit"
            name="login"
            class="btn">

            Login

        </button>

    </form>

    <p class="bottom-text">

        Return to
        <a href="index.php">Home Page</a>

    </p>

</div>

<!-- RIGHT SIDE (IMAGE) -->

<div class="auth-image">

    <img
        src="shield.png.png"
        alt="CyberShield">

</div></div> <!-- END AUTH CONTAINER -->


<script>

function togglePassword(){

    var password = document.getElementById("password");

    if(password.type === "password"){

        password.type = "text";

    }else{

        password.type = "password";

    }

}

</script>


</body>

</html>
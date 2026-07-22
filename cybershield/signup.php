<?php
include "db.php";

$message = "";

if (isset($_POST['signup'])) {

    // ==========================================
    // GET FORM DATA
    // ==========================================

    $first_name = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
    $last_name  = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
    $name = $first_name . " " . $last_name;

    $email = strtolower(trim(mysqli_real_escape_string($conn, $_POST['email'])));

    $phone = preg_replace('/\s+/', '', trim(mysqli_real_escape_string($conn, $_POST['phone'])));

    $cnic = trim(mysqli_real_escape_string($conn, $_POST['cnic']));

    $gender = trim(mysqli_real_escape_string($conn, $_POST['gender']));

    $country = "Pakistan";

    $province = trim(mysqli_real_escape_string($conn, $_POST['province']));

    $city = trim(mysqli_real_escape_string($conn, $_POST['city']));

    $address = trim(mysqli_real_escape_string($conn, $_POST['address']));

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ==========================================
    // VALIDATIONS START
    // ==========================================

    // First Name

    if (empty($first_name)) {

        $message = "First Name is required.";

    }

    elseif (!preg_match("/^[A-Za-z]+(?: [A-Za-z]+)*$/", $first_name)) {

        $message = "First Name can contain only letters.";

    }

    elseif (strlen($first_name) < 2 || strlen($first_name) > 30) {

        $message = "First Name must be between 2 and 30 characters.";

    }

    // Last Name

    elseif (empty($last_name)) {

        $message = "Last Name is required.";

    }

    elseif (!preg_match("/^[A-Za-z]+(?: [A-Za-z]+)*$/", $last_name)) {

        $message = "Last Name can contain only letters.";

    }

    elseif (strlen($last_name) < 2 || strlen($last_name) > 30) {

        $message = "Last Name must be between 2 and 30 characters.";

    }    // ==========================================
    // EMAIL VALIDATION
    // ==========================================

    elseif (empty($email)) {

        $message = "Email Address is required.";

    }

    elseif (strpos($email, ' ') !== false) {

        $message = "Email Address cannot contain spaces.";

    }

    elseif (strlen($email) > 100) {

        $message = "Email Address is too long.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid Email Address.";

    }


    // ==========================================
    // PHONE VALIDATION
    // ==========================================

    elseif (empty($phone)) {

        $message = "Phone Number is required.";

    }

    elseif (!preg_match('/^03[0-9]{9}$/', $phone)) {

        $message = "Phone Number must be in the format 03XXXXXXXXX.";

    }

    elseif (!in_array(substr($phone,0,3),[
        "030","031","032","033","034","035","036","037","038","039"
    ])) {

        $message = "Please enter a valid Pakistani mobile network number.";

    }


    // ==========================================
    // CNIC VALIDATION
    // ==========================================

    elseif (empty($cnic)) {

        $message = "CNIC is required.";

    }

    elseif (!preg_match('/^[0-9]{5}-[0-9]{7}-[0-9]$/',$cnic)) {

        $message = "CNIC format should be XXXXX-XXXXXXX-X.";

    }


    // ==========================================
    // GENDER VALIDATION
    // ==========================================

    elseif (empty($gender)) {

        $message = "Please select your Gender.";

    }

    else{

        // Last digit of CNIC

        $lastDigit = substr($cnic,-1);

        if(($lastDigit % 2 == 0 && $gender != "Female") ||
           ($lastDigit % 2 != 0 && $gender != "Male")){

            $message = "Selected Gender does not match the CNIC.";

        }

    }


    // ==========================================
    // PROVINCE VALIDATION
    // ==========================================

    if(empty($message) && empty($province)){

        $message = "Please select your Province.";

    }


    // ==========================================
    // CITY VALIDATION
    // ==========================================

    if(empty($message) && empty($city)){

        $message = "Please select your City.";

    }


    // ==========================================
    // ADDRESS VALIDATION
    // ==========================================

    if(empty($message) && strlen($address) < 10){

        $message = "Address must contain at least 10 characters.";

    }


    // ==========================================
    // PASSWORD VALIDATION
    // ==========================================

    if(empty($message) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/',$password)){

        $message = "Password must contain Uppercase, Lowercase, Number and Special Character.";

    }


    // ==========================================
    // CONFIRM PASSWORD
    // ==========================================

    if(empty($message) && $password != $confirm_password){

        $message = "Passwords do not match.";

    }
        // ==========================================
    // DATABASE CHECKS & INSERT
    // ==========================================

    if (empty($message)) {

        // Check Email
        $checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if (mysqli_num_rows($checkEmail) > 0) {

            $message = "Email Address is already registered.";

        } else {

            // Check Phone
            $checkPhone = mysqli_query($conn, "SELECT id FROM users WHERE phone='$phone'");

            if (mysqli_num_rows($checkPhone) > 0) {

                $message = "Phone Number is already registered.";

            } else {

                // Check CNIC
                $checkCNIC = mysqli_query($conn, "SELECT id FROM users WHERE cnic='$cnic'");

                if (mysqli_num_rows($checkCNIC) > 0) {

                    $message = "CNIC is already registered.";

                } else {

                    // Encrypt Password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert User
                    $sql = "INSERT INTO users
                    (
                        first_name,
                        last_name,
                        name,
                        email,
                        phone,
                        cnic,
                        gender,
                        country,
                        province,
                        city,
                        address,
                        password
                    )
                    VALUES
                    (
                        '$first_name',
                        '$last_name',
                        '$name',
                        '$email',
                        '$phone',
                        '$cnic',
                        '$gender',
                        '$country',
                        '$province',
                        '$city',
                        '$address',
                        '$hashed_password'
                    )";

                    if (mysqli_query($conn, $sql)) {

    include 'send_email.php';

    $subject = "Welcome to CyberShield";

    $body = "
    <h2>Welcome, $first_name!</h2>

    <p>Your CyberShield account has been created successfully.</p>

    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>

    <p>You can now log in and report cybercrime securely.</p>

    <br>

    <p>Regards,<br><b>CyberShield Team</b></p>
    ";

    sendEmail($email, $name, $subject, $body);

    header("Location: login.php?registered=1");
    exit();

} else {

    $message = "Registration failed. Please try again.";

}
                }

            }

        }

    }

} // END SIGNUP

?>

<!DOCTYPE html>
<html>
<head>

    <title>Sign Up - CyberShield</title>

    <link rel="stylesheet" href="style.css?v=5">

</head>

<body class="auth-page">

<!-- ================= NAVBAR ================= -->

<div class="navbar">

    <div class="logo">

        <img src="shield.png.png" alt="CyberShield">

        <span>CyberShield </span>

    </div>

    <ul>

        <li><a href="index.php">Home</a></li>

        <li><a href="about.php">About Us</a></li>

        <li><a href="contact.php">Contact</a></li>

        <li><a href="login.php">Login</a></li>

    </ul>

</div>

<!-- ================= SIGNUP SECTION ================= -->

<div class="auth-container">

<div class="card">

<h2>Create Your Account</h2>

<p class="subtitle">

CyberShield is available only for citizens and residents of Pakistan.

</p>

<p class="pakistan-text">

🇵🇰 Country : Pakistan

</p>

<?php

if($message!=""){

echo "<div class='error-message'>$message</div>";

}

?>

<form method="POST">

<!-- First Name -->

<input
type="text"
name="first_name"
placeholder="First Name"
required>

<!-- Last Name -->

<input
type="text"
name="last_name"
placeholder="Last Name"
required>

<!-- Email -->

<input
type="email"
name="email"
placeholder="Email Address"
required>

<!-- Phone -->

<input
type="tel"
name="phone"
id="phone"
placeholder="03XXXXXXXXX"
maxlength="11"
required>


<!-- CNIC -->

<input
type="text"
name="cnic"
id="cnic"
placeholder="42101-1234567-1"
maxlength="15"
required>

<!-- Gender -->
        <div class="input-box">
            

            <select name="gender" id="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                
            </select>

        </div>

        

<!-- Province -->

<select
name="province"
id="province"
required>

<option value="">Select Province</option>

<option>Sindh</option>

<option>Punjab</option>

<option>Khyber Pakhtunkhwa</option>

<option>Balochistan</option>

<option>Islamabad Capital Territory</option>

<option>Azad Jammu & Kashmir</option>

<option>Gilgit Baltistan</option>

</select>

<!-- City -->

<select
name="city"
id="city"
required>

<option value="">Select City</option>

</select>

<!-- Address -->

<textarea
name="address"
rows="4"
placeholder="Full Address"
required></textarea>

<!-- Password -->

<div class="password-box">

<input
type="password"
id="password"
name="password"
placeholder="Password"
minlength="8"
required>

<span
class="toggle-password"
onclick="togglePassword()">

👁️

</span>

</div>

<!-- Confirm Password -->

<div class="password-box">

<input
type="password"
id="confirm_password"
name="confirm_password"
placeholder="Confirm Password"
required>

<span
class="toggle-password"
onclick="toggleConfirmPassword()">

👁️

</span>

</div>

<button
class="btn"
name="signup">

Create Account

</button>

</form>

<p class="bottom-text">

Already have an account?

<a href="login.php">

Login

</a>

</p>

</div>

<div class="auth-image">

<img
src="shield.png.png"
alt="CyberShield">

</div>

</div>
<script>

// ========================================
// PROVINCE & CITY DROPDOWN
// ========================================

const cities = {

    "Sindh": [
        "Karachi",
        "Hyderabad",
        "Sukkur",
        "Larkana",
        "Mirpurkhas",
        "Jamshoro",
        "Nawabshah",
        "Badin",
        "Thatta"
    ],

    "Punjab": [
        "Lahore",
        "Rawalpindi",
        "Multan",
        "Faisalabad",
        "Gujranwala",
        "Sialkot",
        "Bahawalpur",
        "Sargodha"
    ],

    "Khyber Pakhtunkhwa": [
        "Peshawar",
        "Mardan",
        "Abbottabad",
        "Swat",
        "Kohat",
        "Bannu",
        "Dera Ismail Khan"
    ],

    "Balochistan": [
        "Quetta",
        "Gwadar",
        "Khuzdar",
        "Turbat",
        "Sibi",
        "Zhob"
    ],

    "Islamabad Capital Territory": [
        "Islamabad"
    ],

    "Azad Jammu & Kashmir": [
        "Muzaffarabad",
        "Mirpur",
        "Kotli",
        "Rawalakot"
    ],

    "Gilgit Baltistan": [
        "Gilgit",
        "Skardu",
        "Hunza",
        "Ghizer"
    ]

};

const province = document.getElementById("province");
const city = document.getElementById("city");

province.addEventListener("change", function(){

    city.innerHTML = "<option value=''>Select City</option>";

    const selectedProvince = this.value;

    if(cities[selectedProvince]){

        cities[selectedProvince].forEach(function(item){

            let option = document.createElement("option");

            option.value = item;
            option.text = item;

            city.appendChild(option);

        });

    }

});

// ========================================
// SHOW / HIDE PASSWORD
// ========================================

function togglePassword(){

    const password = document.getElementById("password");

    password.type =
    password.type === "password"
    ? "text"
    : "password";

}

function toggleConfirmPassword(){

    const confirmPassword =
    document.getElementById("confirm_password");

    confirmPassword.type =
    confirmPassword.type === "password"
    ? "text"
    : "password";

}

// ========================================
// CNIC FORMAT + GENDER AUTO DETECTION
// ========================================

const cnic = document.getElementById("cnic");
const gender = document.getElementById("gender");

cnic.addEventListener("input", function () {

    // Remove everything except numbers
    let numbers = this.value.replace(/\D/g, "");

    // Allow only 13 digits
    if (numbers.length > 13) {
        numbers = numbers.substring(0, 13);
    }

    // Format XXXXX-XXXXXXX-X
    let formatted = "";

    if (numbers.length > 0) {
        formatted += numbers.substring(0, 5);
    }

    // First dash appears immediately after the 6th digit
    if (numbers.length >= 6) {
        formatted += "-" + numbers.substring(5, Math.min(12, numbers.length));
    }

    // Second dash appears before the last digit
    if (numbers.length === 13) {
        formatted += "-" + numbers.substring(12);
    }

    this.value = formatted;
// Reset gender
gender.value = "";

// Detect gender when CNIC is complete
if (numbers.length === 13) {

    let lastDigit = parseInt(numbers.charAt(12));

    if (lastDigit % 2 === 0) {
        gender.value = "Female";
    } else {
        gender.value = "Male";
    }

}
});


// ========================================
// PHONE VALIDATION
// ========================================

const phone = document.getElementById("phone");

phone.addEventListener("input", function(){

    this.value =
    this.value.replace(/[^0-9]/g,"");

});

// ========================================
// PASSWORD MATCH CHECK
// ========================================

document.querySelector("form").addEventListener("submit",function(e){

    const pass =
    document.getElementById("password").value;

    const confirm =
    document.getElementById("confirm_password").value;

    if(pass != confirm){

        alert("Passwords do not match.");

        e.preventDefault();

    }

});

</script>

</body>

</html>
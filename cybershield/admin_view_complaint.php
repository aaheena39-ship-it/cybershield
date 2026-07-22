<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


if(!isset($_GET['id'])){

    header("Location: admin_complaints.php");
    exit();

}


$id = intval($_GET['id']);


$result = mysqli_query($conn,"
SELECT complaints.*, users.name, users.email, users.phone
FROM complaints
LEFT JOIN users
ON complaints.user_id = users.id
WHERE complaints.id='$id'
");


$complaint = mysqli_fetch_assoc($result);


if(!$complaint){

    echo "Complaint not found.";
    exit();

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Complaint Details - CyberShield</title>

<link rel="stylesheet" href="style.css">

</head>


<body class="dashboard-page">


<div class="main">


<h1>Complaint Details</h1>


<div class="activity-box">


<h3>User Information</h3>

<p>
<strong>Name:</strong>
<?php echo htmlspecialchars($complaint['name']); ?>
</p>


<p>
<strong>Email:</strong>
<?php echo htmlspecialchars($complaint['email']); ?>
</p>


<p>
<strong>Phone:</strong>
<?php echo htmlspecialchars($complaint['phone']); ?>
</p>



<hr>



<h3>Complaint Information</h3>


<p>
<strong>Title:</strong>
<?php echo htmlspecialchars($complaint['title']); ?>
</p>


<p>
<strong>Category:</strong>
<?php echo htmlspecialchars($complaint['category']); ?>
</p>


<p>
<strong>Priority:</strong>
<?php echo htmlspecialchars($complaint['priority']); ?>
</p>


<p>
<strong>Status:</strong>
<?php echo htmlspecialchars($complaint['status']); ?>
</p>



<p>

<strong>Description:</strong>

<br><br>

<?php echo nl2br(htmlspecialchars($complaint['description'])); ?>

</p>



<?php if(!empty($complaint['file'])){ ?>

<hr>

<h3>Evidence</h3>


<a href="uploads/<?php echo $complaint['file']; ?>" 
target="_blank"
class="btn-primary">

View Evidence

</a>


<?php } ?>


<br><br>


<a href="admin_complaints.php"
class="btn-outline">

Back

</a>


</div>


</div>


</body>

</html>
<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/signInWindow.css">
	
</head>
<body>

<!--Верхняя панель (чёрная)-->
<header class="header-bar">
	<img src="assets/img/3points.png" class="header-bar-img">
	<p class="header-p">Delete user</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<p class="top-content-text"><a href="mainpage.php">Go to main page</a></p>
</div>

<div class="form-div">
<?php

if(isset($_SESSION['delete_user_id']))
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "lab2db";

	// Встановлення з'єднання
	$conn = new mysqli($servername, $username, $password, $database);
	if ($conn->connect_error)
		die("Connection failed: " . $conn->connect_error);
	
	$sql = "DELETE FROM users WHERE id=". $_SESSION['delete_user_id'];
	if (mysqli_query($conn, $sql))
		echo "Deleted successfully<br>";
	else
	{
		echo "Error: $sql<br>". mysqli_error($conn);
		exit();
	}
	
	
	mysqli_close($conn);
}
else
	echo "Select a user to delete them.<br>";
?>

</div>

	
</body>
</html>
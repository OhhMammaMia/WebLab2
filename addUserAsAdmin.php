<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	
	<link rel="stylesheet" href="assets/css/style.css">
	
</head>
<body>

<!--Верхняя панель (чёрная)-->
<header class="header-bar">
	<img src="assets/img/3points.png" class="header-bar-img">
	<p class="header-p">Add new user</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic">
</div>

<div class="container">

	<?php 
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "lab2db";


		// Встановлення з'єднання
		$conn = new mysqli($servername, $username, $password, $database);
		if ($conn->connect_error)
			die("Connection failed: " . $conn->connect_error);
		
		
	?>
	<div class="form-div">
		<form action="createNewUserAsAdmin.php" method="post" id="reg-form">
			<input type="text" name="firstname" placeholder="First name" required><br>
			<input type="text" name="lastname" placeholder="Last name" required><br>
			<select class="form-fields" name="role" required>
				<option value="" selected>Select role</option>
				<optgroup label="Select role">
				<?php
					$result_role = mysqli_query($conn, "SELECT * FROM roles;");
					$row_role = mysqli_fetch_array($result_role);
					while(is_array($row_role))
					{
						// вывод ролей
						echo "<option value='".$row_role['id']."'>". $row_role['title'] ."</option>";
						$row_role = mysqli_fetch_array($result_role);
					}
					
				?>
			</select><br>
			<input type="email" name="email" placeholder="Email" required><br>
			<input type="password" name="password" placeholder="Password" required><br>
			<input type="password" name="password2" placeholder="Repeat password" required><br>
			<input type="submit" class="btn" value="Sign Up">
		</form>
	</div>
</div>


<script src="assets/js/signInWindow.js"></script>
	
</body>
</html>
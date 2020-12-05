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
	<p class="header-p">Registration results</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<p class="top-content-text"><a href="signUp.php">Sign Up again</a></p>
</div>

<?php 
	// создаём пользователя в БД
	if (count($_POST) > 0)
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "lab2db"; //повинна бути створена в субд

		// Встановлення з'єднання
		$conn = new mysqli($servername, $username, $password, $database);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}



		$first_name = $_POST['firstname'];
		$last_name = $_POST['lastname'];
		$role = (int)$_POST['role'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$password2= $_POST['password2'];
		
		
		$is_successful = true;
		
		echo "<div class='form-div'>";
		if($first_name=="")
		{
			$is_successful = false;
			echo "The 'First name' field is empty.<br>";
		}
		
		if($last_name=="")
		{
			$is_successful = false;
			echo "The 'Last name' field is empty.<br>";
		}
		
		if(strlen($password) < 6 || strlen($password2) < 6)
		{
			$is_successful = false;
			echo "Minimum password length is 6.<br>";
		}
		
		if($password != $password2)
		{
			$is_successful = false;
			echo "Passwords are not equal.<br>";
		}
		if(!$is_successful)
			echo "</div>";
		
		if($is_successful)
		{
			/*$res = mysqli_query($conn, "SELECT email FROM users WHERE email='$email';");
			if ($res != false) {
				echo "The email $email is already taken.";
				exit();
			}*/
			
			
			// проверка на существующий email
			$res = mysqli_query($conn, "SELECT id FROM users WHERE email='$email';");
			if(mysqli_num_rows($res) > 0)
			{
				echo "The email $email is already taken.<br></div>";
				exit();
			}
			
			$sql = "INSERT INTO users (first_name, last_name, email, password, role_id) VALUES ('$first_name', '$last_name', '$email', '$password', '$role');";
			if (mysqli_query($conn, $sql))
				echo "New record created successfully<br>";
			else
			{
				echo "Error: $sql<br>". mysqli_error($conn);
				exit();
			}
			
			// узнаём id пользователя
			$sql = "SELECT id FROM users WHERE email='$email';";
			$res = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($res);
			if (is_array($row))
			{
				$_SESSION['user_id'] = $row['id'];
				mysqli_free_result($res);
			}
			
			$_SESSION['first_name'] = $first_name;
			$_SESSION['email'] = $email;
			
			header('Location: mainpage.php');
		}
	}
	else
		echo 'Incorrect input';

	
mysqli_close($conn);
?>


</body>
</html>
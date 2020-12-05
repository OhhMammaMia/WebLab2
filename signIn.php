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
	<p class="header-p">Authorization results</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<p class="top-content-text"><a href="mainpage.php">Go to main page and try again</a></p>
</div>

<?php 
	if (count($_POST) > 0)
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "lab2db"; //повинна бути створена в субд

		// Встановлення з'єднання
		$conn = new mysqli($servername, $username, $password, $database);
		if ($conn->connect_error)
			die("Connection failed: " . $conn->connect_error);


		$email = $_POST['email'];
		$password = $_POST['password'];
		
		
		$is_successful = true;
		
		echo "<div class='form-div'>";
		if($email=="")
		{
			$is_successful = false;
			echo "The 'Email' field is empty.<br>";
		}
		
		if($password=="")
		{
			$is_successful = false;
			echo "The 'Password' field is empty.<br>";
		}
		
		if(!$is_successful)
			echo "</div>";
		
		// поля заполнены успешно
		if($is_successful)
		{
			// проверка на существующий email
			$sql = "SELECT id, first_name FROM users WHERE email='$email' and password='$password';";
			$res = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($res);
			if (is_array($row))
			{
				//$row = mysqli_fetch_array($res);
				
				$first_name = $row['first_name'];
				
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['first_name'] = $first_name;
				$_SESSION['email'] = $email;
				// очищаем результат
				mysqli_free_result($res);
				
				echo "</div>";
				
				header('Location: mainpage.php');
			}
			else
			{
				echo "This user does not exist. Try again.<br></div>";
				exit();
			}
			
			
		}
			
	}
	else
		echo 'Incorrect input';

	mysqli_close($conn);
?>


</body>
</html>
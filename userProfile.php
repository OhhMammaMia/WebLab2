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
	<p class="header-p">Profile</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<p class="top-content-text"><a href="mainpage.php">Go to main page</a></p>
</div>

<div class="form-div">
<?php

if(isset($_GET['id']))
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "lab2db";

	// Встановлення з'єднання
	$conn = new mysqli($servername, $username, $password, $database);
	if ($conn->connect_error)
		die("Connection failed: " . $conn->connect_error);
	
	$userType = 0; // неавторизован
	
	
	// проверка на авторизацию
	if(isset($_SESSION['email']) && isset($_SESSION['first_name']) 
			&& isset($_SESSION['user_id']))
	{
		echo "You are authorized.<br><br>";
		
		// проверка на админа
		$result_for_rights = mysqli_query($conn, "SELECT role_id FROM users WHERE email='". $_SESSION['email'] ."';");
		if ($result_for_rights)
		{
			// извлечение ассоциативного массива
			if ($row = mysqli_fetch_assoc($result_for_rights))
			{
				if($row['role_id'] == 2)	// админ
					$userType = 2;
				else if($row['role_id'] == 1) // обычный
					$userType = 1;
			}
			// удаление выборки
			mysqli_free_result($result_for_rights);
		}
	}
	else
		echo "You are not authorized.<br><br>";
	
	// админ получает все данные пользователя, сам пользователь получает все данные про себя
	if($userType == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']))
		$sql = "SELECT * FROM users WHERE id='". $_GET['id'] ."';";
	else
		$sql = "SELECT id, first_name, last_name, email, photo, role_id FROM users WHERE id='". $_GET['id'] ."';"; // остальные НЕ получают пароль
	
	$res = mysqli_query($conn, $sql);
	if ($res)
	{
		$row = mysqli_fetch_array($res);
		if(is_array($row))
		{
			echo 	"You are viewing user profile ID: ". $row['id'] ."<br><br><br>";
			
			
					
			echo	"<img src='public/images/". $row['photo'] ."' style='max-width: 300px;' alt='User hasn`t unloaded an image yet'><br>
					<form action='editUser.php' method='post' id='reg-form' enctype='multipart/form-data'>";
			
				if($userType == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']))
					echo "<input type='file' name='fileToUpload' id='fileToUpload' value='Upload photo' class='btn' style='text-align: center;'>";
			
			
				echo	"<br><br>
						<input type='text' name='firstname' placeholder='First name' value='". $row['first_name'] ."' ".
							(($userType == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']))?"required":"disabled") ."><br>
						<input type='text' name='lastname' placeholder='Last name' value='". $row['last_name'] ."' ".
							(($userType == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']))?"required":"disabled") ."><br>
						<input type='email' name='email' placeholder='Email' value='". $row['email'] ."' disabled><br>";
						if($userType==2) // админ может менять роль
						{
							echo "<select class='form-fields' name='role' required>
								<option value=''>Select role</option>
								<optgroup label='Select role'>";
							
							$result_role = mysqli_query($conn, "SELECT * FROM roles;");
							$row_role = mysqli_fetch_array($result_role);
							while(is_array($row_role))
							{
								// вывод ролей
								echo "<option value='".$row_role['id']."' ". ($row_role['id']== $row['role_id']? "selected" : "") .">". $row_role['title'] ."</option>";
								$row_role = mysqli_fetch_array($result_role);
							}
							echo	"</select><br>";
						}
						else 
						{
							$result_role = mysqli_query($conn, "SELECT title FROM roles WHERE id=".$row['role_id'].";");
							$row_role = mysqli_fetch_array($result_role);
							if(is_array($row_role))
								echo "<input type='text' value='". $row_role['title'] ."' disabled><br>";
							else
								echo "<input type='text' value='Sorry. Error :(' disabled><br>";
						}
						
						
					if($userType == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']))
					{
						echo	"<input type='password' name='password' placeholder='Password' value='". $row['password'] ."' required><br>
								<input type='password' name='password2' placeholder='Repeat password' value='". $row['password'] ."' required><br>
								<input type='submit' class='btn' value='Edit'>";
					}
					echo "</form>";
					
					if($userType == 2 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']))
					{
						echo 	"<form action='deleteUser.php' method='post' id='reg-form'>
									<input type='submit' class='btn' value='Delete'>
								</form>";
						$_SESSION['delete_user_id'] = $_GET['id'];
					}
					
			$_SESSION['edit_user_id'] = $_GET['id'];
			
		}
		
		// очищаем результат
		mysqli_free_result($res);
	}

	mysqli_close($conn);
}
else
	echo "Select a user to see thier info.<br>";
?>

</div>

	
</body>
</html>
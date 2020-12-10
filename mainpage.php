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
	<p class="header-p">Main page</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<?php
	// пользователь авторизован
	if(isset($_SESSION['email']) && isset($_SESSION['first_name']))
	{
		echo "<p class='top-content-text'>". $_SESSION['first_name'] ." <b>|</b> <a href='logOut.php'>Log Out</a></p>";
	}
	// пользователь не авторизован
	else
		echo '<p class="top-content-text"><a href="#" onclick="showPopUp()">Sign In</a> <b>|</b> <a href="signUp.php">Sign Up</a></p>';
	
	?>
</div>

<div class="form-div">
<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "lab2db";


	// Встановлення з'єднання
	$conn = new mysqli($servername, $username, $password, $database);
	if ($conn->connect_error)
		die("Connection failed: " . $conn->connect_error);
	
	$is_admin = false;
	
	// проверка на админа
	if(isset($_SESSION['email']) && isset($_SESSION['first_name']))	// проверка на авторизацию
	{
		echo "You are authorized.<br><br>";
		$result_for_rights = mysqli_query($conn, "SELECT role_id FROM users WHERE email='". $_SESSION['email'] ."';");
		if ($result_for_rights)
		{
			// извлечение ассоциативного массива
			while ($row = mysqli_fetch_assoc($result_for_rights))
			{
				if($row['role_id'] == 2)
				{
					$is_admin = true;
				}
			}
			// удаление выборки
			mysqli_free_result($result_for_rights);
		}
	}
	else
		echo "You are not authorized.<br><br>";
	
	$sql = ($is_admin) ?
		"SELECT * FROM users" // админ получает все данные пользователей
		:
		"SELECT id, first_name, last_name, email, role_id FROM users"; // остальные НЕ получают пароль
	
	$res = mysqli_query($conn, $sql);
	if ($res)
	{
		echo "<table cellpadding='7' cellspacing='0' border='2' align='center'>
			<tr style='background-color: #757575'><td>ID</td><td>First name</td><td>Last name</td><td>Email</td>";
		if($is_admin)	echo "<td>Password</td>";
		echo "<td>Role</td></tr>";
		
		$row = mysqli_fetch_array($res);
		$colorVar=1;
		while (is_array($row))
		{
			echo "<tr align='left'".(($colorVar%2==0)?"style='background-color: #d1d1d1'":"").">";

			echo 	"<td align='center'><a style='color: grey; text-decoration: underline;' 
						href='userProfile.php?id=". $row['id'] ."'>". $row['id'] ."</a></td>
					<td>". $row['first_name'] ."</td>
					<td>". $row['last_name'] ."</td>
					<td>". $row['email'] ."</td>";
			if($is_admin)
				echo "<td>". $row['password'] ."</td>";
			
			$result_role = mysqli_query($conn, "SELECT title FROM roles WHERE id='". $row['role_id']. "';");
			$row_role = mysqli_fetch_array($result_role);
			if(is_array($row_role))
			{
				echo "<td>".$row_role['title']."</td>";
			}
			else
				echo "<td>Error while accessing the role info</td>";
				
			echo "</tr>";
			$row = mysqli_fetch_array($res);
			
			$colorVar++;
		}
		echo "</table>";
		
		// очищаем результат
		mysqli_free_result($res);
	}
	
	if($is_admin==true)
	{
		echo	"<br>
				<form action='addUserAsAdmin.php' method='post'>
					<input type='submit' class='btn' value='Add User'>
				</form>";
	}

	mysqli_close($conn);
?>
</div>



<!--Окно авторизации-->
<div class="popup" id="sign-in-window" hidden>
	<div class="popup-content">
		
		<!--Кнопка для закрытия окна-->
		<p align="right"><button onclick="hidePopUp()" class="close-button"><img src="assets/img/x.png" class="little-pic" alt="Close[X]"></button></p>
		
		<hr>
		
		<form action="signIn.php" method="post">
		
			<div class="form-table">
				<div class="field">
					<label for="email">Email</label><input type="email" name="email" required><br>
				</div>
				<div class="field">
					<label for="password">Password</label><input type="password" name="password" required><br>
				</div>
			</div>
			
			<br>
			<hr>

			<p align="right"><input type="submit" class="btn" value="Sign In"></p>
		</form>
		
	</div>
</div>



<script src="assets/js/signInWindow.js"></script>
	
</body>
</html>
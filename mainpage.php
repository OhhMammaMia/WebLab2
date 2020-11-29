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
	<p class="header-p">Users</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<p class="top-content-text"><a href="#" onclick="showPopUp()">Sign In</a> <b>|</b> <a href="signUp.php">Sign Up</a></p>
</div>







<!--Окно авторизации-->
<div class="popup" id="sign-in-window" hidden>
	<div class="popup-content">
		
		<!--Кнопка для закрытия окна-->
		<p align="right"><button onclick="hidePopUp()" class="close-button"><img src="assets/img/x.png" class="little-pic" alt="Close[X]"></button></p>
		
		<hr>
		
		<form action="auth.php" method="post">
		
			<table class="form-table">
				<tr>
					<td class="input-name">Email</td><td><input type="text" name="email"></td><br>
				</tr>
				<tr>
					<td class="input-name">Password</td><td><input type="password" name="password"></td><br>
				</tr>
			</table>
			
			<br>
			<hr>

			<p align="right"><input type="submit" class="btn" value="Sign In"></p>
		</form>
		
	</div>
</div>



<script src="assets/js/signInWindow.js"></script>
	
</body>
</html>
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
	<p class="header-p">Editing results</p>
</header>

<!--Панель с лого и действиями-->
<div class="top-content">
	<img src="assets/img/logo.png" class="little-pic top-content-pic">
	<p class="top-content-text"><a href="mainpage.php">Go to main page</a></p>
</div>

<div class="form-div">
<?php 

// проверка файла на пригодность
$target_dir = "public/images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "File already exists.";
    $uploadOk = 2;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
}
else
{
	// создаём пользователя в БД
	if (count($_POST) > 0 && isset($_SESSION['edit_user_id']))
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
			// добавление пуути файла в БД
			$sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', role_id=$role, password='$password' 
				WHERE id=".$_SESSION['edit_user_id'];
			
			if (mysqli_query($conn, $sql))
				echo "Edited successfully<br>";
			else
			{
				echo "Error: $sql<br>". mysqli_error($conn);
				exit();
			}
			$sql = "UPDATE users SET photo='". basename($_FILES["fileToUpload"]["name"]) ."' WHERE id=".$_SESSION['edit_user_id'];
			if (mysqli_query($conn, $sql))
			{
				echo "New record created successfully<br>";
				
				if($uploadOk!=2)
				{
					// файл загружается на сервер
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
					{
						// добавляю путь в БД
						echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
					}
					else 
						echo "Sorry, there was an error uploading your file.";
				}
				
			}
			else
			{
				echo "Error: $sql<br>". mysqli_error($conn);
				exit();
			}
			
			
			header('Location: mainpage.php');
		}
	}
	else
		echo 'Error :(';

	
	mysqli_close($conn);
}
?>
</div>

</body>
</html>
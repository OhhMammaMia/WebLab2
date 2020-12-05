<?php
// убиваем сессию
	session_start();
	session_destroy();
	header('Location: mainpage.php');
?>
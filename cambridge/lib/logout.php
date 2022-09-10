<?php
	header('X-Powered-By: Pseudorca');
	session_start();
	$old_user = $_SESSION['valid_user'];
	unset($_SESSION['valid_user']);
	unset($_SESSION['username']);
	unset($_SESSION['role']);
	unset($_SESSION['lang']);
	unset($_SESSION['token']);
	session_destroy();
	header("Location: ../");
?>
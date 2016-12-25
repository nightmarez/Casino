<?php
	require_once('utils.php');
	unsetUserCookies();
	header('Location: /login.php');
	die();
?>
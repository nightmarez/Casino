<?php
	require_once('utils.php');

	if (!isUserUnregistered()) {
		header('Location: index.php');
		die();
	}

	if (!isset($_POST['login']) || !isset($_POST['pass'])) {
		header('Location: login.php');
		die();
	}

	$login = htmlspecialchars($_POST['login']);
	$pass = sha1(DB_SALT . $_POST['pass']);
	$remember = isset($_POST['remember']);

	$cookieTime = $remember ? /* one month */ 86400 * 30 : /* one day */ 86400;
	setcookie('login', $login, time() + $cookieTime, '/');
	setcookie('pass', $pass, time() + $cookieTime, '/');
	header('Location: index.php');
?>
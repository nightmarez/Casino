<?php
	if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
	{
		echo 'false';
		die();
	}

	require_once('games.php');

	$login = $_COOKIE['login'];
	$pass = $_COOKIE['pass'];

	$id = getUserId($login, $pass);

	if ($id === false)
	{
		echo 'false';
		die();
	}

	echo lastUnplayed($id);
?>
<?php
	if (!isset($_GET['token']))
	{
		echo 'false';
		die();
	}

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

	$token = $_GET['token'];
	$result = playGame($id, $token);
	$win = $result[0];
	$balls = $result[1];

	if ($balls === false)
	{
		echo 'false';
		die();
	}

	echo $win . '|' . implode(',', $balls);
?>
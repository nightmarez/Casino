<?php
	if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']) || !isset($_GET['money']))
	{
		die();
	}

	$login = htmlspecialchars($_COOKIE['login']);
	$pass = htmlspecialchars($_COOKIE['pass']);
	$money = intval($_GET['money']);

	require_once('utils.php');

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `users` SET `money`=:money WHERE `login`=:login AND `pass`=:pass;');
	$req->bindParam(':money', $money);
	$req->bindParam(':login', $login);
	$req->bindParam(':pass', $pass);
	$req->execute();
?>
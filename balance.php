<?php
	if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
	{
		echo 0;
		die();
	}

	$login = htmlspecialchars($_COOKIE['login']);
	$pass = htmlspecialchars($_COOKIE['pass']);

	require_once('utils.php');

	$db = new PdoDb();
	$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->execute();

	while (list($id, $login, $pass, $level, $activated, $sms, $money) = $req->fetch(PDO::FETCH_NUM))
	{
		echo $money;
		die();
		break;
	}

	echo 0;
?>
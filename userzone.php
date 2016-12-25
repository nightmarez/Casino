<?php
	require_once('utils.php');

	// if admin
	if (isset($_COOKIE['login']) && isset($_COOKIE['pass']))
	{
		$login = $_COOKIE['login'];
		$pass = $_COOKIE['pass'];

		$login = htmlspecialchars($login);
		$pass = htmlspecialchars($pass);

		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=1;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();
		$count = $req->fetchColumn();

		if ($count >= 1)
		{
			header('Location: /admin/');
			die();
		}
	}

	// if not authorized
	if (!isset($_COOKIE['login']) || !isUserExists($_COOKIE['login']))
	{
		header('Location: /');
		die();
	}

	if (isset($_COOKIE['login']) && isUserExists($_COOKIE['login']))
	{
		$login = $_COOKIE['login'];
		$pass = $_COOKIE['pass'];

		$login = htmlspecialchars($login);
		$pass = htmlspecialchars($pass);

		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=2;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();
		$count = $req->fetchColumn();

		if ($count == 0)
		{
			header('Location: /');
			die();
		}
	}
?>
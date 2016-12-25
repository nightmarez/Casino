<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['botid']))
		{
			die();
		}

		$botid = htmlspecialchars($_GET['botid']);

		$db = new PdoDb();
		$req = $db->prepare('INSERT INTO `users` (`login`, `pass`, `level`, `activated`, `money`, `lobbyaccess`) VALUES (:login, :login, 2, 1, 10000000, 1);');
		$req->bindParam(':login', $botid, PDO::PARAM_INT);
		$req->execute();

		echo 'OK';
	}
?>
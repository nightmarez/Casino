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
		$db->beginTransaction();

		$req = $db->prepare('SELECT `id` FROM `users` WHERE `login`=:login;');
		$req->bindParam(':login', $botid, PDO::PARAM_INT);
		$req->execute();
		$id = intval($req->fetch(PDO::FETCH_ASSOC)['id']);

		$req = $db->prepare('DELETE FROM `users` WHERE `login`=:login;');
		$req->bindParam(':login', $botid, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('DELETE FROM `pgames` WHERE `user`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		echo 'OK';
	}
?>
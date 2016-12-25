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

		// get bot id
		$req = $db->prepare('SELECT `id` FROM `users` WHERE `login`=:login;');
		$req->bindParam(':login', $botid, PDO::PARAM_INT);
		$req->execute();
		$id = intval($req->fetch(PDO::FETCH_ASSOC)['id']);

		// get total games
		$req = $db->prepare('SELECT COUNT(*) FROM `pgames` WHERE `user`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$games = intval($req->fetchColumn());

		// get total spent
		$req = $db->prepare('SELECT SUM(`bet` * `betlines`) AS total FROM `pgames` WHERE `user`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$spent = intval($req->fetch(PDO::FETCH_ASSOC)['total']);

		// get total win
		$req = $db->prepare('SELECT SUM(`win`) AS total FROM `pgames` WHERE `user`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$win = intval($req->fetch(PDO::FETCH_ASSOC)['total']);

		// result
		echo $games . '|' . $spent . '|' . $win;
		$db->commit();
	}
?>
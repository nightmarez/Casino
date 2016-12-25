<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['game']) || !isset($_GET['access'])) {
			die();
		} else {
			$game = intval($_GET['game']);
			$access = intval($_GET['access']);

			$db = new PdoDb();
			$req = $db->prepare('UPDATE `programs` SET `access`=:access WHERE `id`=:id;');
			$req->bindParam(':id', $game, PDO::PARAM_INT);
			$req->bindParam(':access', $access, PDO::PARAM_INT);
			$req->execute();

			echo 'OK';
		}
	}
?>
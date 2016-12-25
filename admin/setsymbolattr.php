<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['game']) || !isset($_GET['id']) || !isset($_GET['value'])) {
			die();
		} else {
			$game = intval($_GET['game']);
			$id = intval($_GET['id']);
			$value = intval($_GET['value']);

			$db = new PdoDb();
			$req = $db->prepare('UPDATE `symbols` SET `attr`=:value WHERE `id`=:id AND `gameid`=:game;');
			$req->bindParam(':id', $id, PDO::PARAM_INT);
			$req->bindParam(':game', $game, PDO::PARAM_INT);
			$req->bindParam(':value', $value, PDO::PARAM_INT);
			$req->execute();

			echo 'OK';
		}
	}
?>
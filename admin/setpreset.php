<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['game']) || !isset($_GET['preset'])) {
			die();
		} else {
			$game = intval($_GET['game']);
			$preset = htmlspecialchars($_GET['preset']);

			$db = new PdoDb();
			$req = $db->prepare('UPDATE `programs` SET `preset`=:preset WHERE `id`=:id;');
			$req->bindParam(':id', $game, PDO::PARAM_INT);
			$req->bindParam(':preset', $preset, PDO::PARAM_STR);
			$req->execute();

			echo 'OK';
		}
	}
?>
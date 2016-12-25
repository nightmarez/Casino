<?php
	require_once('../utils.php');

	if (!dealerZoneAccess())
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
			$preset = intval($_GET['preset']);

			$db = new PdoDb();
			$req = $db->prepare('UPDATE `programs` SET `preset`=:preset WHERE `id`=:id;');
			$req->bindParam(':id', $id, PDO::PARAM_INT);
			$req->bindParam(':preset', $preset, PDO::PARAM_INT);
			$req->execute();

			echo 'OK';
		}
	}
?>
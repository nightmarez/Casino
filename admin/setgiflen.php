<?php
	//ini_set('error_reporting', E_ALL);
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);

	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		$game = htmlspecialchars($_GET['game']);
		$file = htmlspecialchars($_GET['file']);
		$hash = htmlspecialchars($_GET['hash']);
		$len = htmlspecialchars($_GET['len']);
		$gamepath = htmlspecialchars($gamepath);

		$db = new PdoDb();
		$req = $db->prepare('DELETE FROM `giflen` WHERE `game` = :gamepath AND `name` = :file;');
		$req->bindParam(':gamepath', $gamepath, PDO::PARAM_STR);
		$req->bindParam(':file', $file, PDO::PARAM_STR);
		$req->execute();

		$req = $db->prepare('INSERT INTO `giflen` (`game`, `name`, `hash`, `len`) VALUES (:game, :file, :hash, :len);');
		$req->bindParam(':game', $game, PDO::PARAM_STR);
		$req->bindParam(':name', $name, PDO::PARAM_STR);
		$req->bindParam(':hash', $hash, PDO::PARAM_STR);
		$req->bindParam(':len', $len, PDO::PARAM_INT);
		$req->execute();
	}
?>
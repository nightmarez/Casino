<?php
	require_once('../../utils.php');

	$game = htmlspecialchars($_GET['game']);
	$file = htmlspecialchars($_GET['file']);

	$db = new PdoDb();
	$req = $db->prepare('SELECT `len` FROM `giflen` WHERE `game`=:game AND `name`=:file;');
	$req->bindParam(':game', $game, PDO::PARAM_STR);
	$req->bindParam(':file', $file, PDO::PARAM_STR);
	$req->execute();

	while (list($len) = $req->fetch(PDO::FETCH_NUM))
	{
		echo $len;
		die();
		break;
	}

	echo 'false';
?>

	
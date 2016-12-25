<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['gameid']))
		{
			die();
		}

		$gameid = intval($_GET['gameid']);

		if (!isset($_GET['name']))
		{
			die();
		}

		$presetid = htmlspecialchars($_GET['name']);

		$db = new PdoDb();
		$db->beginTransaction();

		// get reels count
		$req = $db->prepare('SELECT `reels` FROM `programs` WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();

		$reelsCount = 0;
		while (list($reels) = $req->fetch(PDO::FETCH_NUM))
		{
			$reelsCount = intval($reels);
			break;
		}

		// add preset
		for ($i = 0; $i < $reelsCount; ++$i)
		{
			$req = $db->prepare('INSERT INTO `presets` (`gameid`, `presetid`, `stringid`, `preset`) VALUES (:gameid, :presetid, :stringid, "");');
			$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
			$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
			$req->bindParam(':stringid', $i, PDO::PARAM_INT);
			$req->execute();
		}

		// commit
		$db->commit();
		echo 'OK';
	}
?>
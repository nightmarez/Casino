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
			header('Location: /admin/tapes.php');
			die();
		}

		$gameid = intval($_GET['gameid']);

		if (!isset($_GET['index']))
		{
			header('Location: /admin/tapes.php');
			die();
		}

		$index = intval($_GET['index']);

		if (!isset($_GET['presetid']))
		{
			header('Location: /admin/tapes.php');
			die();
		}

		$presetid = htmlspecialchars($_GET['presetid']);

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

		// remove selected reel
		if ($reelsCount > $index)
		{
			$req = $db->prepare('DELETE FROM `presets` WHERE `gameid`=:gameid AND `stringid`=:index;');
			$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
			$req->bindParam(':index', $index, PDO::PARAM_INT);
			$req->execute();
		}

		// update programs table
		--$reelsCount;
		$req = $db->prepare('UPDATE `programs` SET `reels`=:count WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':count', $reelsCount, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		header('Location: /admin/tapes.php?gameid=' . $gameid . '&presetid=' . $presetid);
	}
?>
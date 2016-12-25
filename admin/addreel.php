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

		if (!isset($_GET['presetid']))
		{
			header('Location: /admin/tapes.php');
			die();
		}

		$presetid = intval($_GET['presetid']);

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

		// add new reel
		$req = $db->prepare('INSERT INTO `presets` (`gameid`, `presetid`, `stringid`) VALUES(:gameid, :presetid, :stringid);');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
		$req->bindParam(':stringid', $reelsCount, PDO::PARAM_INT);
		$req->execute();

		// update programs table
		++$reelsCount;
		$req = $db->prepare('UPDATE `programs` SET `reels`=:count WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':count', $reelsCount, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		header('Location: /admin/tapes.php?gameid=' . $gameid . '&presetid=' . $presetid);
	}
?>
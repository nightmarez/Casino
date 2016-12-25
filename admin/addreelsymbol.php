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

		if (!isset($_GET['name']))
		{
			header('Location: /admin/tapes.php');
			die();
		}

		$symbolname = htmlspecialchars($_GET['name']);

		if (!isset($_GET['presetid']))
		{
			header('Location: /admin/tapes.php');
			die();
		}

		$presetid = htmlspecialchars($_GET['presetid']);

		if (!isset($_GET['stringid']))
		{
			header('Location: /admin/tapes.php');
			die();
		}

		$stringid = intval($_GET['stringid']);

		$db = new PdoDb();
		$db->beginTransaction();

		$req = $db->prepare('SELECT `preset` FROM `presets` WHERE `gameid`=:gameid AND `presetid`=:presetid AND `stringid`=:stringid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':presetid', $presetid, PDO::PARAM_INT);
		$req->bindParam(':stringid', $stringid, PDO::PARAM_INT);
		$req->execute();

		$presetstring = '';
		while (list($preset) = $req->fetch(PDO::FETCH_NUM))
		{
			$presetstring = $preset;
			break;
		}

		$symbolname = "'" . $symbolname . "'";
		if (strlen($presetstring) > 0) {
			$presetstring = $presetstring . ',' . $symbolname;
		} else {
			$presetstring = $symbolname;
		}

		$req = $db->prepare('UPDATE `presets` SET `preset`=:preset WHERE `gameid`=:gameid AND `presetid`=:presetid AND `stringid`=:stringid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
		$req->bindParam(':stringid', $stringid, PDO::PARAM_INT);
		$req->bindParam(':preset', $presetstring, PDO::PARAM_STR);
		$req->execute();

		$db->commit();
		header('Location: /admin/tapes.php?gameid=' . $gameid . '&presetid=' . $presetid);
	}
?>
<?php
	require_once('../../utils.php');
	
	if (!lobbyZoneAccess())
	{
		echo 'false';
		die();
	}

	if (!isset($_GET['gameid']))
	{
		echo 'false';
		die();
	}

	$gameid = intval($_GET['gameid']);

	// open db
	$db = new PdoDb();
	$db->beginTransaction();

	// uniq access token
	$guid = htmlspecialchars(uniqid('token_', true));

	// get current preset for this game
	$req = $db->prepare('SELECT `preset` FROM `programs` WHERE `id`=:gameid;');
	$req->bindParam(':gameid', $gameid, PDO::PARAM_STR);
	$req->execute();
	$presetid = htmlspecialchars($req->fetch(PDO::FETCH_ASSOC)['preset']);

	// create record for current game
	$req = $db->prepare('INSERT INTO `pgames` (`guid`, `presetid`) VALUES (:guid, :presetid);');
	$req->bindParam(':guid', $guid, PDO::PARAM_STR);
	$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
	$req->execute();

	// get id for current game
	$req = $db->prepare('SELECT `id` FROM `pgames` WHERE `guid`=:guid;');
	$req->bindParam(':guid', $guid, PDO::PARAM_STR);
	$req->execute();
	$id = intval($req->fetch(PDO::FETCH_ASSOC)['id']);

	// get offset for current game machine
	$req = $db->prepare('SELECT `offset` FROM `programs` WHERE `id`=:gameid;');
	$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
	$req->execute();
	$offset = intval($req->fetch(PDO::FETCH_ASSOC)['offset']);

	require_once('rnd.php');
	$spin = array();

	/*
	$old = $offset;
	for ($j = 0; $j < 5; ++$j) {
		$old = $old + 4 + randomNumberGet(6);

		if ($j == 0) {
			$offset = $old;
		}

		$spin[] = $old;
	}
	*/

	$old = 0;
	for ($j = 0; $j < 5; ++$j) {
		$old = $old + ($j == 0 ? 4 + randomNumberGet(6) : 6);

		if ($j == 0) {
			$offset = $old;
		}

		$spin[] = $old;
	}

	// set offset for current game machine
	$req = $db->prepare('UPDATE `programs` SET `offset`=:offset WHERE `id`=:gameid;');
	$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
	$req->bindParam(':offset', $offset, PDO::PARAM_INT);
	$req->execute();

	// done
	$db->commit();
	echo json_encode(array(
		'id' => $id,
		'guid' => $guid,
		'spin' => $spin
	));
?>
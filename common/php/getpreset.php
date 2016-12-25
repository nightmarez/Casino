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

	if (isset($_GET['presetid']))
	{
		$presetid = htmlspecialchars($_GET['presetid']);
	}

	// open db
	$db = new PdoDb();
	$db->beginTransaction();

	// get current preset id
	if (!isset($presetid) || !strlen($presetid))
	{
		$req = $db->prepare('SELECT `preset` FROM `programs` WHERE `id`=:id;');
		$req->bindParam(':id', $gameid, PDO::PARAM_STR);
		$req->execute();
		$presetid = $req->fetch(PDO::FETCH_ASSOC)['preset'];
	}

	if (strlen($presetid)) {
		$presetid = htmlspecialchars($presetid);

		// get current preset strings
		$req = $db->prepare('SELECT `preset` FROM `presets` WHERE `gameid`=:gameid AND `presetid`=:presetid ORDER BY `stringid`;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
		$req->execute();
		$arr = $req->fetchAll(PDO::FETCH_NUM);

		$preset = implode('|', array_map('array_shift', array_values($arr)));
		
		echo json_encode(array(
			'presetid' => $presetid,
			'preset' => $preset
		));
	} else {
		// TEMPORARY:
		// generate random preset

		$x = array();
		for ($i = 0; $i < 5; ++$i) {
			$xx = array();

			for ($j = 0; $j < 20; ++$j) {
				$xx[] = array("'aa'", "'bb'", "'cc'", "'dd'", "'ee'", "'ff'")[rand() % 6];
			}

			$x[] = implode(',', array_values($xx));
		}

		$preset = implode('|', $x);

		echo json_encode(array(
			'presetid' => 0,
			'preset' => $preset
		));
	}	

	// done
	$db->commit();
?>